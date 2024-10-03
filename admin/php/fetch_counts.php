<?php
require '../../config.php';

// Fetch counts
$counts = [];

// Fetch customers count
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM customers WHERE is_verified = 1");
$counts['customers'] = $stmt->fetchColumn();

// Fetch raiders count
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM raiders WHERE is_verified = 1");
$counts['raiders'] = $stmt->fetchColumn();

// Fetch users count
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM users");
$counts['users'] = $stmt->fetchColumn();

// Output JSON
echo json_encode($counts);
?>
