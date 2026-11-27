<?php
// Test with backticks
$query1 = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
$query2 = "SHOW INDEX FROM `wp_bookingdates` WHERE Name = 'booking_id_dates'";

echo "Query 1: $query1\n";
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query1, $matches)) {
    echo "  Table: " . $matches[1] . "\n";
}

echo "\nQuery 2: $query2\n";
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query2, $matches)) {
    echo "  Table: " . $matches[1] . "\n";
}
?>