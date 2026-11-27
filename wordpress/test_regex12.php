<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";

echo "Query: $query\n\n";

// Pattern from wpdb.php
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query, $matches)) {
    echo "Table name: " . $matches[1] . "\n";
} else {
    echo "No match\n";
}

// Simpler pattern
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+/i', $query, $matches)) {
    $after_from = substr($query, strlen($matches[0]));
    echo "After FROM: $after_from\n";
    
    // Find the first space after WHERE
    $pos = strpos($after_from, ' WHERE');
    if ($pos !== false) {
        $table = substr($after_from, 0, $pos);
        echo "Table: " . trim($table) . "\n";
    }
}
?>