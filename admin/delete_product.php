<?php
include("../auth.php");
include("../db.php");

// 1. Check if ID exists
if(!isset($_GET['id'])){
    header("Location: view.php");
    exit();
}

// 2. Security: Clean the ID to prevent SQL Injection
$id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. Fetch product image first
$query = "SELECT image FROM products WHERE id='$id'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);

    // 4. Delete Image from folder if it exists
    $image_path = "../assets/images/" . $row['image'];
    
    if($row['image'] != "" && file_exists($image_path)){
        unlink($image_path); // Delete the file
    }

    // 5. Delete Record from Database
    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
}

// 6. Redirect back to the Product List (view.php)
header("Location: view.php?msg=deleted");
exit();
?>