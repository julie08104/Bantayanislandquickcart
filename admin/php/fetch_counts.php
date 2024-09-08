<?php
require '../../config.php';

// Fetch counts
$counts = [];

// Fetch customers count
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM customers");
$counts['customers'] = $stmt->fetchColumn();

// Fetch raiders count
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM raiders");
$counts['raiders'] = $stmt->fetchColumn();

// Fetch users count
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM users");
$counts['users'] = $stmt->fetchColumn();

// Output JSON
echo json_encode($counts);
?>
