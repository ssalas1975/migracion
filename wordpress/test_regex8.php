<?php
// Test with various table names and values
$test_cases = [
    "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'",
    "SHOW INDEX FROM wp_bookingdates WHERE Name = booking_id_dates",
    "SHOW INDEX FROM `wp_bookingdates` WHERE Name = 'booking_id_dates'",
    "SHOW INDEX FROM wp_bookingdates WHERE Name = `booking_id_dates`",
    "SHOW INDEX FROM wp_bookingdates WHERE `Name` = 'booking_id_dates'",
];

foreach ($test_cases as $query) {
    echo "Query: $query\n";
    if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query, $matches)) {
        echo "  Table: " . $matches[1] . "\n";
    } else {
        echo "  No match\n";
    }
    echo "\n";
}
?>