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
    $result = $conn->query("SELECT * FROM categories");
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode(['success' => true, 'categories' => $categories]);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id   = $data['id'] ?? 0;
    $name = $data['name'] ?? '';

    $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? 0;
    $stmt = $conn->prepare("DELETE FROM categories WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Category deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Delete failed']);
    }
}