<?php
include("db.php");

$offers = mysqli_query($conn, "SELECT id, title FROM offers WHERE product_id IS NULL OR product_id = 0");
$count = 0;
while($offer = mysqli_fetch_assoc($offers)) {
    $title = mysqli_real_escape_string($conn, $offer['title']);
    $res = mysqli_query($conn, "SELECT id FROM products WHERE name = '$title' LIMIT 1");
    if($prod = mysqli_fetch_assoc($res)) {
        $pid = $prod['id'];
        mysqli_query($conn, "UPDATE offers SET product_id = $pid WHERE id = " . $offer['id']);
        $count++;
    }
}
echo "Auto-linked $count offers to products by name.";
?>
