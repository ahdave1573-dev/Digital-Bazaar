<?php
include("db.php");
$res = mysqli_query($conn, "SHOW TABLES LIKE 'cart'");
if(mysqli_num_rows($res) > 0) {
    echo "cart table exists\n";
    $res2 = mysqli_query($conn, "DESCRIBE cart");
    while($row = mysqli_fetch_assoc($res2)) print_r($row);
} else {
    echo "cart table DOES NOT exist\n";
}
?>
