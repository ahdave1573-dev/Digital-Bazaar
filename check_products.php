<?php
include("db.php");
$res = mysqli_query($conn, "SELECT id, name FROM products");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
