<?php
include("db.php");
$res = mysqli_query($conn, "DESCRIBE offers");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
