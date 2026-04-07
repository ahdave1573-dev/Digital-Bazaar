<?php
include('../db.php');

// Add address columns if they don't exist
$alter_query = "ALTER TABLE orders 
    ADD COLUMN address TEXT AFTER user_id,
    ADD COLUMN city VARCHAR(100) AFTER address,
    ADD COLUMN pincode VARCHAR(20) AFTER city";

if(mysqli_query($conn, $alter_query)){
    echo "Successfully added address, city, and pincode columns to orders table.";
} else {
    echo "Error updating table (Columns might already exist): " . mysqli_error($conn);
}
?>
