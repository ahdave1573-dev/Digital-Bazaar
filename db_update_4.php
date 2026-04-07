<?php
include("db.php");

// 1. Update order_items to include original_price and discount_percentage
$sql1 = "ALTER TABLE order_items 
         ADD COLUMN original_price DECIMAL(10,2) AFTER product_name,
         ADD COLUMN discount_percentage INT AFTER original_price";

// 2. Create cart table for persistent cart storage (optional but requested)
$sql2 = "CREATE TABLE IF NOT EXISTS cart (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    original_price DECIMAL(10,2) NOT NULL,
    discount_percentage INT(11) DEFAULT 0,
    discounted_price DECIMAL(10,2) NOT NULL,
    quantity INT(11) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if(mysqli_query($conn, $sql1)) {
    echo "order_items updated successfully. ";
} else {
    echo "Error updating order_items: " . mysqli_error($conn) . " ";
}

if(mysqli_query($conn, $sql2)) {
    echo "cart table created successfully.";
} else {
    echo "Error creating cart table: " . mysqli_error($conn);
}
?>
