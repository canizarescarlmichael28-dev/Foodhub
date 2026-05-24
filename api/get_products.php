<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$username = "root";
$password = "";
$dbname = "foodhub"; // <-- Siguroha nga mao ni ngalan sa inyong DB sa phpMyAdmin

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    echo json_encode(["error" => "Failed to connect to database: " . mysqli_connect_error()]);
    exit();
}

$query = "SELECT name, price FROM products"; // <-- Siguroha nga 'products' ang table name ninyo
$result = mysqli_query($conn, $query);

$products = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}

echo json_encode($products);
mysqli_close($conn);
?>