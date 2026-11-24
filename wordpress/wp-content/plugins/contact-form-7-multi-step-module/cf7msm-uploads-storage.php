<?php
/**
 * Storage, expiry and cleanup for files retained between multi-step forms.
 *
 * Retaining uploads is a Pro feature (see cf7msm-uploads__premium_only.php),
 * but this file ships in every build. The cleanup cron, deactivation and
 * uninstall handling must keep working after a downgrade or when a license
 * expires so retained files are never stranded on disk.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Retained files live outside the WordPress media library and are managed
// directly, the same way Contact Form 7 manages its own temporary uploads.
// phpcs:disable WordPress.WP.AlternativeFunctions

define( 'CF7MSM_UPLOADS_CRON_HOOK', 'cf7msm_cleanup_uploads' );
define( 'CF7MSM_UPLOADS_DIR_NAME', 'cf7msm_uploads' );
define( 'CF7MSM_UPLOADS_MANIFEST', 'manifest.json' );

/**
 * Return how long retained files are kept after the last successful step.
 */
function cf7msm_upload_ttl() {
    $ttl = (int) apply_filters( 'cf7msm_upload_ttl', 6 * HOUR_IN_SECONDS );
    if ( $ttl <= 0 ) {
        $ttl = 6 * HOUR_IN_SECONDS;
    }
    return $ttl;
}

/**
 * Return the root directory for retained files, without a trailing slash.
 */
function cf7msm_uploads_base_dir() {
    $upload_dir = wp_upload_dir( null, false );
    $base_dir = !empty( $upload_dir['basedir'] ) ? $upload_dir['basedir'] : WP_CONTENT_DIR . '/uploads';
    $dir = untrailingslashit( path_join( $base_dir, CF7MSM_UPLOADS_DIR_NAME ) );

    /**
     * Filter the directory that holds files retained between steps.
     *
     * The directory must be inside WP_CONTENT_DIR, UPLOADS or WP_TEMP_DIR,
     * because Contact Form 7 only attaches files from those locations. Use a
     * directory dedicated to this plugin: it receives a deny-all .htaccess.
     *
     * @param string $dir Absolute directory path.
     */
    return untrailingslashit( (string) apply_filters( 'cf7msm_upload_dir', $dir ) );
}

/**
 * Return the directory name used for a flow.
 *
 * The name is an HMAC of the flow id, so a leaked flow cookie does not reveal
 * where the flow's files are stored.
 */
function cf7msm_uploads_flow_dir_name( $flow_id ) {
    if ( !cf7msm_is_valid_pipe_flow_id( $flow_id ) ) {
        return '';
    }
    return substr( hash_hmac( 'sha256', $flow_id, wp_salt( 'auth' ) ), 0, 40 );
}

/**
 * Return the absolute directory for a flow's retained files.
 */
function cf7msm_uploads_flow_dir( $flow_id ) {
    $name = cf7msm_uploads_flow_dir_name( $flow_id );
    if ( '' === $name ) {
        return '';
    }
    return cf7msm_uploads_base_dir() . '/' . $name;
}

/**
 * Return whether a directory name looks like a flow directory.
 */
function cf7msm_uploads_is_flow_dir_name( $name ) {
    return is_string( $name ) && 1 === preg_match( '/^[a-f0-9]{40}$/', $name );
}

/**
 * Decode a manifest's contents. Returns null when it is not a manifest.
 */
function cf7msm_uploads_decode_manifest( $contents ) {
    if ( !is_string( $contents ) || '' === $contents ) {
        return null;
    }
    $manifest = json_decode( $contents, true );
    if ( !is_array( $manifest ) || !isset( $manifest['expires'] ) ) {
        return null;
    }
    $manifest['expires'] = (int) $manifest['expires'];
    if ( empty( $manifest['fields'] ) || !is_array( $manifest['fields'] ) ) {
        $manifest['fields'] = array();
    }
    return $manifest;
}

/**
 * Read a flow directory's manifest under a shared lock.
 *
 * @param string $flow_dir Absolute flow directory.
 * @return array|null The manifest, or null when it is missing or unreadable.
 */
function cf7msm_uploads_read_manifest_file( $flow_dir ) {
    $path = $flow_dir . '/' . CF7MSM_UPLOADS_MANIFEST;
    if ( !is_file( $path ) || !is_readable( $path ) ) {
        return null;
    }

    $handle = @fopen( $path, 'rb' );
    if ( false === $handle ) {
        return null;
    }
    $contents = '';
    if ( flock( $handle, LOCK_SH ) ) {
        $contents = stream_get_contents( $handle );
        flock( $handle, LOCK_UN );
    }
    fclose( $handle );

    return cf7msm_uploads_decode_manifest( $contents );
}

/**
 * Return a flow's manifest when it exists and has not expired.
 */
function cf7msm_uploads_get_manifest( $flow_id ) {
    $flow_dir = cf7msm_uploads_flow_dir( $flow_id );
    if ( '' === $flow_dir || !is_dir( $flow_dir ) ) {
        return null;
    }
    $manifest = cf7msm_uploads_read_manifest_file( $flow_dir );
    if ( null === $manifest || $manifest['expires'] <= time() ) {
        return null;
    }
    return $manifest;
}

/**
 * Return whether a flow has an unexpired file manifest.
 */
function cf7msm_uploads_flow_exists( $flow_id ) {
    return null !== cf7msm_uploads_get_manifest( $flow_id );
}

/**
 * Recursively delete a directory inside the retained-files root.
 *
 * Symbolic links are removed, never followed.
 */
function cf7msm_uploads_rmdir( $dir ) {
    $base_dir = cf7msm_uploads_base_dir();
    $dir = untrailingslashit( $dir );
    if ( '' === $dir || 0 !== strpos( wp_normalize_path( $dir ) . '/', wp_normalize_path( $base_dir ) . '/' ) ) {
        return false;
    }
    if ( is_link( $dir ) ) {
        return @unlink( $dir );
    }
    if ( !is_dir( $dir ) ) {
        return true;
    }

    $entries = @scandir( $dir );
    if ( false === $entries ) {
        return false;
    }
    foreach ( $entries as $entry ) {
        if ( '.' === $entry || '..' === $entry ) {
            continue;
        }
        $path = $dir . '/' . $entry;
        if ( is_dir( $path ) && !is_link( $path ) ) {
            cf7msm_uploads_rmdir( $path );
        }
        else {
            @unlink( $path );
        }
    }
    return @rmdir( $dir );
}

/**
 * Delete every retained file for a flow.
 */
function cf7msm_uploads_delete_flow( $flow_id ) {
    $flow_dir = cf7msm_uploads_flow_dir( $flow_id );
    if ( '' === $flow_dir ) {
        return;
    }
    cf7msm_uploads_rmdir( $flow_dir );
}

/**
 * Cron callback: delete expired flows and abandoned directories.
 *
 * A flow is deleted when its manifest has expired. A directory whose manifest
 * is missing or unreadable is deleted once its modification time is older
 * than the retention period plus one hour.
 */
function cf7msm_uploads_cleanup() {
    $base_dir = cf7msm_uploads_base_dir();
    if ( !is_dir( $base_dir ) ) {
        return;
    }
    $entries = @scandir( $base_dir );
    if ( false === $entries ) {
        return;
    }

    $now = time();
    $orphan_age = cf7msm_upload_ttl() + HOUR_IN_SECONDS;
    foreach ( $entries as $entry ) {
        if ( !cf7msm_uploads_is_flow_dir_name( $entry ) ) {
            continue;
        }
        $flow_dir = $base_dir . '/' . $entry;
        if ( !is_dir( $flow_dir ) || is_link( $flow_dir ) ) {
            continue;
        }

        $manifest = cf7msm_uploads_read_manifest_file( $flow_dir );
        if ( null !== $manifest ) {
            if ( $manifest['expires'] <= $now ) {
                cf7msm_uploads_rmdir( $flow_dir );
            }
            continue;
        }

        $mtime = (int) @filemtime( $flow_dir );
        if ( $mtime > 0 && $mtime + $orphan_age < $now ) {
            cf7msm_uploads_rmdir( $flow_dir );
        }
    }
}
add_action( CF7MSM_UPLOADS_CRON_HOOK, 'cf7msm_uploads_cleanup' );

/**
 * Schedule the hourly cleanup when it is not already scheduled.
 */
function cf7msm_uploads_schedule_cleanup() {
    if ( !wp_next_scheduled( CF7MSM_UPLOADS_CRON_HOOK ) ) {
        wp_schedule_event( time() + HOUR_IN_SECONDS, 'hourly', CF7MSM_UPLOADS_CRON_HOOK );
    }
}

/**
 * Remove the scheduled cleanup.
 */
function cf7msm_uploads_unschedule_cleanup() {
    wp_clear_scheduled_hook( CF7MSM_UPLOADS_CRON_HOOK );
}

/**
 * Return the access-protection files written to the retained-files root.
 *
 * Apache honors .htaccess. Nginx ignores it, so the readme documents the
 * matching rule; the HMAC and random directory names are the fallback.
 */
function cf7msm_uploads_protection_files() {
    return array(
        '.htaccess' => "# Apache 2.4+\n<IfModule authz_core_module>\n    Require all denied\n</IfModule>\n\n"
            . "# Apache 2.2\n<IfModule !authz_core_module>\n    Deny from all\n</IfModule>\n",
        'index.php' => "<?php\n// Silence is golden.\n",
    );
}

/**
 * Delete all retained files and the scheduled cleanup on uninstall.
 *
 * Only flow directories and the files this plugin wrote are removed, so a
 * filtered upload directory that holds other files is left in place.
 */
function cf7msm_uploads_uninstall() {
    cf7msm_uploads_unschedule_cleanup();
    delete_option( '_cf7msm_upload_error' );

    $base_dir = cf7msm_uploads_base_dir();
    $entries = is_dir( $base_dir ) ? @scandir( $base_dir ) : false;
    if ( false === $entries ) {
        return;
    }
    foreach ( $entries as $entry ) {
        if ( cf7msm_uploads_is_flow_dir_name( $entry ) ) {
            cf7msm_uploads_rmdir( $base_dir . '/' . $entry );
        }
    }
    foreach ( cf7msm_uploads_protection_files() as $file => $contents ) {
        $path = $base_dir . '/' . $file;
        if ( is_file( $path ) && @file_get_contents( $path ) === $contents ) {
            @unlink( $path );
        }
    }
    // Removed only when empty.
    @rmdir( $base_dir );
}
