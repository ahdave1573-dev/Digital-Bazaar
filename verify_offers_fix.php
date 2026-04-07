<?php
include("db.php");

function test_query($conn, $description, $where_clause) {
    echo "Testing: $description\n";
    $sql = "SELECT id, title, active, end_date FROM offers " . $where_clause;
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        echo "Error: " . mysqli_error($conn) . "\n";
        return;
    }
    $count = mysqli_num_rows($res);
    echo "Found $count matches.\n";
    while ($row = mysqli_fetch_assoc($res)) {
        echo " - ID: {$row['id']}, Title: {$row['title']}, Active: {$row['active']}, End: {$row['end_date']}\n";
    }
    echo "---------------------------------\n";
}

$base_filter = "WHERE active = 1 AND (end_date IS NULL OR end_date >= NOW())";

// Test 1: Current active/valid offers
test_query($conn, "Current logic result", $base_filter);

// Test 2: Checking what is excluded
test_query($conn, "Excluded (Expired or Inactive)", "WHERE active = 0 OR (end_date IS NOT NULL AND end_date < NOW())");

?>
