<?php
require '../../config.php';

session_start();

// Fetch user data
$stmt = $pdo->query("SELECT id, name, email, is_verified FROM users WHERE id != ".$_SESSION['user_id']);
$users = $stmt->fetchAll();

// Output as JSON
echo json_encode([
    "data" => $users
]);
?>
