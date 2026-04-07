<?php
include("db.php");
$res = mysqli_query($conn, "SELECT id, title, product_id, active FROM offers");
if(!$res) die("Query failed: " . mysqli_error($conn));
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
