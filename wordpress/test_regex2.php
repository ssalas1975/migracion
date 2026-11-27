<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
echo "Query: $query\n\n";

if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE)/i', $query, $matches)) {
    echo "Table name (first match): " . $matches[1] . "\n";
}

if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE)/i', $query, $matches)) {
    echo "Table name (preg_match): " . $matches[1] . "\n";
}

if (preg_match('/^SHOW\s+INDEX\s+FROM\s+/i', $query, $matches)) {
    echo "After FROM: " . substr($query, strlen($matches[0])) . "\n";
}
?>