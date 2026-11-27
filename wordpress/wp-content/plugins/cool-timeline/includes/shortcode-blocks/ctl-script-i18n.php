<?php
/**
 * Keep the block editor JSON catalog in step with the plugin .po files.
 *
 * wp_set_script_translations() reads languages/cool-timeline-{locale}-ctl-block-js.json.
 * Loco Translate updates the .po file only, so a newer .po must be compiled into that JSON
 * before the editor script prints.
 *
 * @package Cool_Timeline
 */

if ( ! defined( 'ABSPATH' ) && PHP_SAPI !== 'cli' ) {
	exit;
}

/**
 * Rebuild the editor JSON for one locale when its .po file is newer.
 *
 * @param string $locale Locale code, such as fr_FR.
 * @param string $dir    Languages directory, with no trailing slash.
 * @return bool True when a JSON file was written.
 */
function ctl_sync_block_script_jed( $locale, $dir ) {
	$locale = preg_replace( '/[^A-Za-z0-9_]/', '', (string) $locale );
	if ( '' === $locale ) {
		return false;
	}

	$po   = $dir . '/cool-timeline-' . $locale . '.po';
	$json = $dir . '/cool-timeline-' . $locale . '-ctl-block-js.json';
	if ( ! is_readable( $po ) ) {
		return false;
	}
	if ( is_readable( $json ) && filemtime( $json ) >= filemtime( $po ) ) {
		return false;
	}

	$built = ctl_build_block_script_jed( $po, $locale );
	if ( ! $built ) {
		return false;
	}

	if ( ! is_dir( $dir ) || ! is_writable( $dir ) ) {
		return false;
	}

	return false !== file_put_contents( $json, $built );
}

/**
 * Compile one .po file into the JED document used by wp.i18n.
 *
 * @param string $po_file Path to the .po file.
 * @param string $locale  Locale code.
 * @return string|false JSON, or false when the file cannot be read.
 */
function ctl_build_block_script_jed( $po_file, $locale ) {
	$parsed = ctl_parse_po_file( $po_file );
	if ( ! $parsed ) {
		return false;
	}

	$messages = array(
		'' => array(
			'domain'       => 'cool-timeline',
			'lang'         => $locale,
			'plural-forms' => $parsed['plural'],
		),
	);

	foreach ( $parsed['entries'] as $entry ) {
		if ( '' === $entry['id'] || '' === $entry['str'] || ! empty( $entry['fuzzy'] ) ) {
			continue;
		}
		$key              = $entry['context'] ? $entry['context'] . "\004" . $entry['id'] : $entry['id'];
		$messages[ $key ] = $entry['plurals'] ? $entry['plurals'] : array( $entry['str'] );
	}

	$data  = array(
		'domain'      => 'cool-timeline',
		'locale_data' => array(
			'cool-timeline' => $messages,
		),
	);
	$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
	$json  = function_exists( 'wp_json_encode' ) ? wp_json_encode( $data, $flags ) : json_encode( $data, $flags );

	return is_string( $json ) ? $json : false;
}

/**
 * Read msgid/msgstr pairs from a gettext catalog.
 *
 * @param string $path Path to the .po file.
 * @return array|false
 */
function ctl_parse_po_file( $path ) {
	$raw = file_get_contents( $path );
	if ( ! is_string( $raw ) || '' === $raw ) {
		return false;
	}

	$lines   = preg_split( "/\r\n|\n|\r/", $raw );
	$entries = array();
	$current = null;
	$mode    = '';
	$fuzzy   = false;
	$header  = '';

	$commit = function () use ( &$entries, &$current, &$header ) {
		if ( ! is_array( $current ) ) {
			return;
		}
		if ( '' === $current['id'] ) {
			$header = $current['str'];
		} else {
			$entries[] = $current;
		}
		$current = null;
	};

	foreach ( $lines as $line ) {
		if ( isset( $line[0] ) && '#' === $line[0] ) {
			if ( 0 === strpos( $line, '#,' ) && false !== strpos( $line, 'fuzzy' ) ) {
				$fuzzy = true;
			}
			continue;
		}

		if ( 0 === strpos( $line, 'msgctxt ' ) ) {
			$commit();
			$current = array(
				'id'      => '',
				'str'     => '',
				'context' => ctl_unquote_po( substr( $line, 8 ) ),
				'plurals' => array(),
				'fuzzy'   => $fuzzy,
			);
			$mode    = 'ctxt';
			$fuzzy   = false;
			continue;
		}

		if ( 0 === strpos( $line, 'msgid ' ) ) {
			if ( ! is_array( $current ) || 'ctxt' !== $mode ) {
				$commit();
				$current = array(
					'id'      => '',
					'str'     => '',
					'context' => '',
					'plurals' => array(),
					'fuzzy'   => $fuzzy,
				);
				$fuzzy = false;
			}
			$current['id'] = ctl_unquote_po( substr( $line, 6 ) );
			$mode          = 'id';
			continue;
		}

		if ( 0 === strpos( $line, 'msgid_plural ' ) ) {
			$mode = 'plural_id';
			continue;
		}

		if ( preg_match( '/^msgstr(?:\[(\d+)\])? (.*)$/', $line, $match ) ) {
			$text = ctl_unquote_po( $match[2] );
			if ( '' !== $match[1] ) {
				$current['plurals'][ (int) $match[1] ] = $text;
				if ( 0 === (int) $match[1] ) {
					$current['str'] = $text;
				}
			} else {
				$current['str'] = $text;
			}
			$mode = 'str';
			continue;
		}

		if ( isset( $line[0] ) && '"' === $line[0] && is_array( $current ) ) {
			$text = ctl_unquote_po( $line );
			if ( 'id' === $mode ) {
				$current['id'] .= $text;
			} elseif ( 'ctxt' === $mode ) {
				$current['context'] .= $text;
			} elseif ( 'str' === $mode ) {
				if ( $current['plurals'] ) {
					$last                         = max( array_keys( $current['plurals'] ) );
					$current['plurals'][ $last ] .= $text;
					if ( 0 === $last ) {
						$current['str'] .= $text;
					}
				} else {
					$current['str'] .= $text;
				}
			}
		}
	}
	$commit();

	$plural = 'nplurals=2; plural=n != 1;';
	if ( preg_match( '/Plural-Forms:\s*([^\n]+)/', $header, $match ) ) {
		$plural = trim( $match[1] );
	}

	return array(
		'plural'  => $plural,
		'entries' => $entries,
	);
}

/**
 * Decode one quoted PO string.
 *
 * @param string $quoted Quoted line, including the surrounding quotes.
 * @return string
 */
function ctl_unquote_po( $quoted ) {
	$quoted = trim( $quoted );
	if ( strlen( $quoted ) >= 2 && '"' === $quoted[0] && '"' === substr( $quoted, -1 ) ) {
		$quoted = substr( $quoted, 1, -1 );
	}
	return stripcslashes( $quoted );
}

/**
 * Sync the JSON catalog for the current site locale.
 */
function ctl_sync_block_script_translation() {
	if ( ! defined( 'CTL_PLUGIN_DIR' ) ) {
		return;
	}
	$locale = function_exists( 'determine_locale' ) ? determine_locale() : '';
	if ( ! $locale ) {
		return;
	}
	ctl_sync_block_script_jed( $locale, untrailingslashit( CTL_PLUGIN_DIR . 'languages' ) );
}

if ( PHP_SAPI === 'cli' && isset( $argv[0] ) && realpath( $argv[0] ) === __FILE__ ) {
	$dir = dirname( __DIR__, 2 ) . '/languages';
	foreach ( glob( $dir . '/cool-timeline-*.po' ) as $po ) {
		if ( ! preg_match( '/cool-timeline-([A-Za-z0-9_]+)\.po$/', basename( $po ), $match ) ) {
			continue;
		}
		$wrote = ctl_sync_block_script_jed( $match[1], $dir );
		echo $match[1] . ( $wrote ? " updated\n" : " unchanged\n" );
	}
}
