<?php
require '../../config.php';

// Fetch user data
$stmt = $pdo->query("SELECT id, name, location FROM stores");
$stores = $stmt->fetchAll();

// Output as JSON
echo json_encode([
    "data" => $stores
]);
?>
