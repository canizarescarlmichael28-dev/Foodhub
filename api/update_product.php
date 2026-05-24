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

$data = json_decode(file_get_contents("php://input"), true);

$id          = $data['id'] ?? 0;
$name        = $data['name'] ?? '';
$description = $data['description'] ?? '';
$price       = $data['price'] ?? 0;
$stock       = $data['stock'] ?? 0;
$image       = $data['image'] ?? '';

if (empty($id) || empty($name) || empty($price)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?");
$stmt->bind_param("ssdisi", $name, $description, $price, $stock, $image, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}