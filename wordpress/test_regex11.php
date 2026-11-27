<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";

echo "Query: $query\n\n";

// The exact pattern from wpdb.php
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query, $matches)) {
    echo "Table name extracted: " . $matches[1] . "\n";
} else {
    echo "Regex did not match\n";
}

// Also test with the second pattern (SHOW TABLE STATUS)
if (preg_match('/^\s*SHOW\s+(?:TABLE\s+STATUS|(?:FULL\s+)?TABLES).+WHERE\s+Name\s*=\s*["\']((?:[0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF]))+[\'"]\1/i', $query, $maybe)) {
    echo "Table from second pattern: " . $maybe[2] . "\n";
}
?>