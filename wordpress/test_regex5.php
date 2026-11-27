<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
echo "Query: $query\n\n";

if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query, $matches)) {
    echo "Table name: " . $matches[1] . "\n";
} else {
    echo "Regex did not match\n";
}
?>