<?php
// 1. એરર રિપોર્ટિંગ અને JSON હેડર
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

include 'db_connect.php';

// 2. બધા ઓર્ડર લાવો (છેલ્લો ઓર્ડર પહેલા - DESC)
$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = $conn->query($sql);

$orders = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// 3. JSON પ્રિન્ટ કરો
echo json_encode($orders);

$conn->close();
?>