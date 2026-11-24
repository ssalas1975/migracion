<?php
/**
 * Persistent anonymous install identity (option redi_restaurant_install).
 * Shared by API telemetry headers and deactivation feedback.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('ReDiInstall')) {
    class ReDiInstall
    {
        const OPTION = 'redi_restaurant_install';

        /**
         * Returns the install record, creating it if missing.
         * Existing installs (upgraded, never re-activated) get an id with activated_at = null.
         */
        public static function ensure($is_activation = false)
        {
            $install = get_option(self::OPTION);
            $changed = false;

            if (!is_array($install) || empty($install['id'])) {
                $install = array(
                    'id'                 => wp_generate_uuid4(),
                    'first_activated_at' => $is_activation ? time() : null,
                    'activated_at'       => $is_activation ? time() : null,
                );
                $changed = true;
            } elseif ($is_activation) {
                $install['activated_at'] = time();
                if (empty($install['first_activated_at'])) {
                    $install['first_activated_at'] = $install['activated_at'];
                }
                $changed = true;
            }

            if ($changed) {
                update_option(self::OPTION, $install, false);
            }
            return $install;
        }

        public static function id()
        {
            $install = self::ensure();
            return (!empty($install['id'])) ? (string) $install['id'] : '';
        }

        public static function delete()
        {
            delete_option(self::OPTION);
        }
    }
}
