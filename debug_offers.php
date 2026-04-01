<?php
include("config/db.php");

$offers = mysqli_query($conn, "SELECT id, title, image FROM offers LIMIT 5");

echo "<h3>Offers in Database:</h3>";
while($row = mysqli_fetch_assoc($offers)) {
    echo "ID: " . $row['id'] . "<br>";
    echo "Title: " . $row['title'] . "<br>";
    echo "Image Path: " . $row['image'] . "<br>";
    echo "Full Path Would Be: assets/images/offers/" . basename($row['image']) . "<br>";
    echo "File Exists: " . (file_exists("assets/images/offers/" . basename($row['image'])) ? "YES" : "NO") . "<br>";
    echo "<img src='assets/images/offers/" . basename($row['image']) . "' width='100'><br>";
    echo "---<br>";
}
?>
