<?php
$query = "SHOW INDEX FROM wp_bookingdates WHERE Name = 'booking_id_dates'";
echo "Query: $query\n\n";

// Simulate the get_table_from_query function
function get_table_from_query($query) {
    $query = rtrim( $query, "\;/-" );
    $query = ltrim( $query, "\r\n\t (" );
    $query = preg_replace( "/\((?!\\s*select)[^(]*?\)/is", "()", $query );
    $query = preg_replace( "/^SET STATEMENT.+?\\sFOR\\s+/is", "", $query );
    
    // Pattern 1: SELECT, INSERT, UPDATE, DELETE
    if (preg_match(
        "/^\\s*(?:"
            . "SELECT.*?\\s+FROM"
            . "|INSERT(?:\\s+LOW_PRIORITY|\\s+DELAYED|\\s+HIGH_PRIORITY)?(?:\\s+IGNORE)?(?:\\s+INTO)?"
            . "|REPLACE(?:\\s+LOW_PRIORITY|\\s+DELAYED)?(?:\\s+INTO)?"
            . "|UPDATE(?:\\s+LOW_PRIORITY)?(?:\\s+IGNORE)?"
            . "|DELETE(?:\\s+LOW_PRIORITY|\\s+QUICK|\\s+IGNORE)*(?:.+?FROM)?"
        . ")*\\s+((?:[0-9a-zA-Z\\$_.`-]|[\xC2-\xDF][\x80-\xBF])+)/is",
        $query,
        $maybe
    ) ) {
        return str_replace( "`", "", $maybe[1] );
    }
    
    // Pattern 2: SHOW INDEX FROM
    if (preg_match('/^SHOW\s+INDEX\s+FROM\s+([\s\S]+?)(?=\s*WHERE\s+Name\s*=)/i', $query, $matches)) {
        return $matches[1];
    }
    
    // Pattern 3: SHOW TABLE STATUS / TABLES WHERE Name =
    if (preg_match('/^\s*SHOW\s+(?:TABLE\s+STATUS|(?:FULL\s+)?TABLES).+WHERE\s+Name\s*=\s*["\']((?:[0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF])+)\1/is', $query, $maybe)) {
        return $maybe[2];
    }
    
    // Pattern 4: SHOW TABLE STATUS / TABLES LIKE
    if (preg_match('/^\s*SHOW\s+(?:TABLE\s+STATUS|(?:FULL\s+)?TABLES)\s+(?:WHERE\s+Name\s+)?LIKE\s*["\']((?:[\\0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF])+)%?["\']/is', $query, $maybe)) {
        return str_replace( "\\\_", "_", $maybe[2] );
    }
    
    return false;
}

$table = get_table_from_query($query);
echo "Extracted table: " . ($table ? $table : 'false') . "\n";
?>