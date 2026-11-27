<?php
// Test the regex pattern
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+[\s\S]+?WHERE\s+Name\s*=\s*[\'"]((?:[0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF]))+/i', $query, $matches)) {
    echo "Table name extracted: " . $matches[1] . "\n";
} else {
    echo "Regex did not match\n";
}
?>