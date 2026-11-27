<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
echo "Query: $query\n\n";

// The issue: [\s\S]+? is non-greedy, but it can still match past the first WHERE if needed
// Let's try a simpler pattern
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s*Name)/i', $query, $matches)) {
    echo "Table name (lookahead): " . $matches[1] . "\n";
}

// Better: use a pattern that stops at the first space after WHERE
if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query, $matches)) {
    echo "Table name (with = in lookahead): " . $matches[1] . "\n";
}
?>