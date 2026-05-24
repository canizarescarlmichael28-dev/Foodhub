<?php
include_once 'config/db.php';
$email = 'admin@foodhub.com';
$password = 'password';
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
echo "User found: " . ($user ? 'YES' : 'NO') . "<br>";
if ($user) {
    echo "Password verify: " . (password_verify($password, $user['password']) ? 'YES' : 'NO') . "<br>";
    echo "Role: " . $user['role'];
}