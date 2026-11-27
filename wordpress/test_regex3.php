<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
echo "Query: $query\n\n";

if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE)/i', $query, $matches)) {
    echo "Table name: " . $matches[1] . "\n";
}

// Now test the actual pattern from wpdb.php
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+[\s\S]+?WHERE\s+Name\s*=\s*[\'"]((?:[0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF]))+/i', $query, $matches)) {
    echo "Captured table name: " . $matches[1] . "\n";
} else {
    echo "Regex did not match with full pattern\n";
}
?>