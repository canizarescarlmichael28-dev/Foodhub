<?php
include_once 'config/db.php';
$hash = password_hash('password', PASSWORD_BCRYPT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = 'admin@foodhub.com'");
$stmt->bind_param("s", $hash);
$stmt->execute();
echo "Done! New hash: " . $hash;