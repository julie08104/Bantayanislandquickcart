<?php
require '../../config.php';

// Fetch user data
$stmt = $pdo->query("SELECT id, CONCAT(firstname, ' ', lastname) AS fullname, email, phone, address, is_verified FROM customers WHERE is_verified = 1");
$customers = $stmt->fetchAll();

// Output as JSON
echo json_encode([
    "data" => $customers
]);
?>
