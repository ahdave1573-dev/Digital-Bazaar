<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "digitalbazaar";

// Connection Create Karna
$conn = mysqli_connect($host, $username, $password, $database);

// Connection Check Karna
if(!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>