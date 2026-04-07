<?php
include('db.php');
$names = ['JBL Tune 770NC Wireless Over Ear ANC Headphones with Mic', 'PTron Dynamo Power 20000mAh 22.5W Super Fast Charging'];
foreach($names as $n) {
    $n_safe = mysqli_real_escape_string($conn, $n);
    $res = mysqli_query($conn, "SELECT id, name FROM products WHERE name = '$n_safe'");
    if($row = mysqli_fetch_assoc($res)) {
        echo "Found '$n' with ID: " . $row['id'] . "\n";
    } else {
        echo "DID NOT FIND '$n'\n";
    }
}
?>
