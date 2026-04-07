<?php
include("db.php");

$result = mysqli_query($conn, "SELECT * FROM offers");

if ($result) {
    echo "<pre>";
    echo "Total Offers: " . mysqli_num_rows($result) . "\n";
    while($row = mysqli_fetch_assoc($result)) {
        print_r($row);
        echo "\n";
    }
    echo "</pre>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
