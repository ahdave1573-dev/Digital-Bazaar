<?php
include('db.php');
echo "ORDERS TABLE:\n";
$res = mysqli_query($conn, "DESCRIBE orders");
while($row = mysqli_fetch_assoc($res)) print_r($row);

echo "\nORDER_ITEMS TABLE:\n";
$res = mysqli_query($conn, "DESCRIBE order_items");
while($row = mysqli_fetch_assoc($res)) print_r($row);
?>
