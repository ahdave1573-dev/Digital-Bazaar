<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auth logic here

if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}
?>
