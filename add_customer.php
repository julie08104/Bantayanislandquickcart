<?php
// add_customer.php
header('Content-Type: application/json');
include 'database_connection.php'; // Your PDO connection script

$name = $_POST['name'];
$lastname = $_POST['lastname'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$company = isset($_POST['company']) ? $_POST['company'] : null;

$stmt = $pdo->prepare("INSERT INTO customers (name, lastname, address, contact, email, company) VALUES (?, ?, ?, ?, ?, ?)");
$success = $stmt->execute([$name, $lastname, $address, $contact, $email, $company]);

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Customer added successfully' : 'Failed to add customer'
]);
?>
