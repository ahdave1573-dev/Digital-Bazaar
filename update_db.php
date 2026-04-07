<?php
include('db.php');
mysqli_query($conn, "UPDATE offers SET product_id = 60 WHERE id = 4");
mysqli_query($conn, "UPDATE offers SET product_id = 63 WHERE id = 3");
echo "Updated " . mysqli_affected_rows($conn) . " rows.";
?>
