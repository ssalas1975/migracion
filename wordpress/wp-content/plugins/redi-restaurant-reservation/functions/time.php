<?php

class ReDiTime
{
    public static function getReservationTime($persons, $default_duration)
    {
        // Define the path to the file
        $parentDirectory = dirname(plugin_dir_path(__FILE__));
        $filename = $parentDirectory . '/reservationtime.json';
    
        // Check if the file exists and $persons is provided
        if (file_exists($filename) && $persons) {
            // Load the WP_Filesystem object
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
    
            // Initialize WP_Filesystem
            $wp_filesystem = null;
            WP_Filesystem();
    
            // Check if WP_Filesystem was successfully initialized
            if ( ! is_null( $GLOBALS['wp_filesystem'] ) ) {
                // Get the file content using WP_Filesystem
                $file_content = $GLOBALS['wp_filesystem']->get_contents($filename);
    
                // Decode JSON content
                $json = json_decode($file_content, true);
    
                // Check if JSON is valid and if the persons key exists
                if ($json !== null && array_key_exists($persons, $json)) {
                    return (int)$json[$persons];
                }
            }
        }
    
        // Return the default duration if file doesn't exist, JSON is invalid, or key not found
        return $default_duration;
    }

    public static function loadCustomDurations()
    {
        // Load durations from config and show to users to select
        $parentDirectory = dirname(plugin_dir_path(__FILE__));
        $filename = $parentDirectory . '/customduration.json';

        if (file_exists($filename)) {
            return json_decode(file_get_contents($filename), true);
        }

        return null;
    }

    public static function convert_to_js_format($format)
    {
        $convert = array(
//Day       ---     ---
//Week      ---     ---
//Month     ---     ---
//Year      ---     ---
//Time      ---     ---

//%I    Two digit representation of the hour in 12-hour format  01 through 12
//hh    Hours; leading zero for single-digit hours (12-hour clock).
            'I' => 'hh',
//%l (lower-case 'L')   Hour in 12-hour format, with a space preceding single digits    1 through 12
//h     Hours; no leading zero for single-digit hours (12-hour clock).
            'l' => 'h',
//%k    Two digit representation of the hour in 24-hour format, with a space preceding single digits    0 through 23
//H     Hours; no leading zero for single-digit hours (24-hour clock).
            'k' => 'H',
//%H    Two digit representation of the hour in 24-hour format  00 through 23
//HH    Hours; leading zero for single-digit hours (24-hour clock).
            'H' => 'HH',
//%M    Two digit representation of the minute  00 through 59
//MM    Minutes; leading zero for single-digit minutes.
            'M' => 'MM',
//%P    lower-case 'am' or 'pm' based on the given time     Example: am for 00:31, pm for 22:23
//tt    Lowercase, two-character time marker string: am or pm.
            'P' => 'tt',
//%p    UPPER-CASE 'AM' or 'PM' based on the given time     Example: AM for 00:31, PM for 22:23
//TT    Uppercase, two-character time marker string: AM or PM.
            'p' => 'TT',
        );

        $result = '';
        foreach (str_split($format) as $char) {
            if ($char == '%') {
                $result .= '';
            } elseif (array_key_exists($char, $convert)) {
                $result .= $convert[$char];
            } else {
                $result .= $char;
            }
        }

        return $result;
    }

    public static function dropdown_time_format()
    {
        $wp_time_format = get_option('time_format');
        $wp_time_format_array = str_split($wp_time_format);
        foreach ($wp_time_format_array as $index => $format_char) // some users have G \h i \m\i\n
        {
            if ($format_char === '\\') {
                $wp_time_format_array[$index] = '';
                if (isset($wp_time_format_array[$index + 1])) {
                    $wp_time_format_array[$index + 1] = '';
                }
            }
        }
        $wp_time_format = implode('', $wp_time_format_array);
        $is_am_pm = strpos($wp_time_format, 'g');
        $is_am_pm_lead_zero = strpos($wp_time_format, 'h');

        $is_24 = strpos($wp_time_format, 'G');
        $is_24_lead_zero = strpos($wp_time_format, 'H');

        if ($is_am_pm !== false || $is_am_pm_lead_zero !== false) {
            $a = stripos($wp_time_format, 'a');
            $am_text = '';
            if ($a !== false) {
                $am_text = $wp_time_format[$a];
            }
            if ($is_am_pm !== false) {
                return $wp_time_format[$is_am_pm] . ' ' . $am_text;
            }
            if ($is_am_pm_lead_zero !== false) {
                return $wp_time_format[$is_am_pm_lead_zero] . ' ' . $am_text;
            }
        }
        if ($is_24 !== false) {
            return $wp_time_format[$is_24];
        }
        if ($is_24_lead_zero !== false) {
            return $wp_time_format[$is_24_lead_zero];
        }

        return 'H'; //if no time format found use 24 h with lead zero
    }

    public static function format_time($start_time, $language, $format)
    {

        global $q_config;
        if (function_exists('qtrans_convertTimeFormat')) {// time format from qTranslate
            $format = $q_config['time_format'][$language];

            return qtrans_strftime($format, strtotime($start_time));
        } elseif (function_exists('ppqtrans_convertTimeFormat')) { //and qTranslate Plus
            $format = $q_config['time_format'][$language];

            return ppqtrans_strftime($format, strtotime($start_time));
        }

        return gmdate($format, strtotime($start_time));
    }

    public static function getAlternativeTimeStep($options, $persons = 0)
    {
        // Define the path to the file
        $filename = plugin_dir_path(__FILE__) . 'alternativetimestep.json';
    
        // Check if the file exists
        if (file_exists($filename) && $persons) {
            // Include file handling functions
            require_once(ABSPATH . 'wp-admin/includes/file.php');
    
            // Initialize WP_Filesystem
            $wp_filesystem = null;
            WP_Filesystem();
    
            // Check if WP_Filesystem was successfully initialized
            if (!is_null($GLOBALS['wp_filesystem'])) {
                // Get the file content using WP_Filesystem
                $file_content = $GLOBALS['wp_filesystem']->get_contents($filename);
    
                // Decode JSON content
                $json = json_decode($file_content, true);
    
                // Check if JSON is valid and if the persons key exists
                if ($json !== null && array_key_exists($persons, $json)) {
                    return (int)$json[$persons];
                }
            }
        }

        // Fallback to option value or default value
        if (isset($options['AlternativeTimeStep']) && $options['AlternativeTimeStep'] > 0) {
            return (int)$options['AlternativeTimeStep'];
        }
    
        return 30; // Default value
    }
    


    public static function load_time_format($lang, $default_time_format)
    {
        // Define the path to the file
        $parentDirectory = dirname(plugin_dir_path(__FILE__));
        $filename = $parentDirectory . '/time_format.json';
    
        // Check if the file exists
        if (file_exists($filename)) {
            // Include file handling functions
            require_once(ABSPATH . 'wp-admin/includes/file.php');
    
            // Initialize WP_Filesystem
            $wp_filesystem = null;
            WP_Filesystem();
    
            // Check if WP_Filesystem was successfully initialized
            if (!is_null($GLOBALS['wp_filesystem'])) {
                // Get the file content using WP_Filesystem
                $file_content = $GLOBALS['wp_filesystem']->get_contents($filename);
    
                // Decode JSON content
                $json = json_decode($file_content, true);
    
                // Check if JSON is valid and if the language key exists
                if ($json !== null && array_key_exists($lang, $json)) {
                    return $json[$lang];
                }
            }
        }
    
        // Return the default time format if file doesn't exist, JSON is invalid, or key not found
        return $default_time_format;
    }

    /**
     * Parse a user-entered time into 24h "HH:MM", or false if invalid.
     * Empty / whitespace-only input returns "" (closed day — not an error).
     *
     * Accepts: 21:00, 9:00, 09:00, 9:00 am, 9:00am, 9:00 AM, 9 pm, 9pm,
     * 9.00 pm, 9:00 p.m., 21.00, extra spaces. 12:00 am → 00:00,
     * 12:00 pm → 12:00, 24:00 → 00:00.
     *
     * @param mixed $value
     * @return string|false
     */
    public static function normalize_time($value)
    {
        return redi_normalize_time($value);
    }

    /**
     * Format a stored 24h "HH:MM" time for admin display using WordPress time_format.
     *
     * @param string $hhmm
     * @param string|null $format Optional PHP date format; defaults to get_option('time_format').
     * @return string
     */
    public static function format_working_time_for_display($hhmm, $format = null)
    {
        if ($hhmm === null || $hhmm === '') {
            return '';
        }

        $normalized = redi_normalize_time($hhmm);
        if ($normalized === false || $normalized === '') {
            return (string) $hhmm;
        }

        if ($format === null || $format === '') {
            $format = function_exists('get_option') ? get_option('time_format', 'H:i') : 'H:i';
        }

        if ($format === null || $format === '') {
            $format = 'H:i';
        }

        $timestamp = strtotime('1970-01-01 ' . $normalized . ' UTC');
        if ($timestamp === false) {
            return $normalized;
        }

        return gmdate($format, $timestamp);
    }
}

/**
 * Parse a user-entered time into 24h "HH:MM".
 *
 * @param mixed $value
 * @return string|false Empty string for blank input; "HH:MM" when valid; false when invalid.
 */
function redi_normalize_time($value)
{
    if ($value === null) {
        return '';
    }

    $value = is_string($value) ? $value : (string) $value;
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    // Collapse internal whitespace and normalize separators / am-pm markers.
    $value = preg_replace('/\s+/u', ' ', $value);
    $value = str_ireplace(array('a.m.', 'p.m.'), array('am', 'pm'), $value);
    $value = str_replace(',', ':', $value);

    // 24h or 12h with optional minutes and optional am/pm. Dot or colon as separator.
    $pattern = '/^(\d{1,2})(?:[:.](\d{1,2}))?\s*(am|pm)?$/i';
    if (!preg_match($pattern, $value, $matches)) {
        return false;
    }

    $hour = (int) $matches[1];
    $minute = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : 0;
    $ampm = isset($matches[3]) ? strtolower($matches[3]) : '';

    if ($minute < 0 || $minute > 59) {
        return false;
    }

    if ($ampm !== '') {
        if ($hour < 1 || $hour > 12) {
            return false;
        }
        if ($ampm === 'am') {
            $hour = ($hour === 12) ? 0 : $hour;
        } else {
            $hour = ($hour === 12) ? 12 : $hour + 12;
        }
    } else {
        // Allow 24:00 as midnight; otherwise 0–23 only.
        if ($hour === 24) {
            if ($minute !== 0) {
                return false;
            }
            $hour = 0;
        } elseif ($hour < 0 || $hour > 23) {
            return false;
        }
    }

    return sprintf('%02d:%02d', $hour, $minute);
}
?>