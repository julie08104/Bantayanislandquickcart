<?php
require '../../config.php';

// Fetch user data
$stmt = $pdo->query("SELECT id, CONCAT(firstname, ' ', lastname) AS fullname, email, phone, address, vehicle_type, vehicle_number, is_verified FROM raiders");
$raiders = $stmt->fetchAll();

// Output as JSON
echo json_encode([
    "data" => $raiders
]);
?>
