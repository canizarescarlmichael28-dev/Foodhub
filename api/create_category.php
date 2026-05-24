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
$name = $data['name'] ?? '';

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $conn->insert_id, 'name' => $name]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create category']);
}
