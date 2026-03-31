<?php
include('config/db.php');
$result = mysqli_query($conn, "DESCRIBE products");
while($row = mysqli_fetch_assoc($result)){
    print_r($row);
}
?>
