<?php
// Test with and without quotes
$query_with_quotes = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
$query_without_quotes = "SHOW INDEX FROM wp_bookingdates WHERE Name = booking_id_dates";

echo "With quotes:\n";
echo $query_with_quotes . "\n";
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query_with_quotes, $matches)) {
    echo "Table: " . $matches[1] . "\n";
}

echo "\nWithout quotes:\n";
echo $query_without_quotes . "\n";
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query_without_quotes, $matches)) {
    echo "Table: " . $matches[1] . "\n";
}
?>