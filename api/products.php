<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM products");
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(['success' => true, 'products' => $products]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name        = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price       = $data['price'] ?? 0;

    if (empty($name) || empty($price)) {
        echo json_encode(['success' => false, 'message' => 'Name and price are required']);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $description, $price);

    if ($stmt->execute()) {
        $id = $conn->insert_id;
        echo json_encode(['success' => true, 'id' => $id, 'name' => $name, 'description' => $description, 'price' => $price]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create product']);
    }
}