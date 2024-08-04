<?php
// Include your database connection or initialization script
include 'db_connection.php';
require_once '../init.php'; // Adjust path as necessary
header('Content-Type: application/json');
 // Ensure your DB connection is included

$response = array('success' => false, 'message' => 'Something went wrong.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $company = isset($_POST['company']) ? $_POST['company'] : null;

    try {
        $stmt = $pdo->prepare("INSERT INTO customer (name, lastname, address, contact, email, company) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $lastname, $address, $contact, $email, $company])) {
            $response['success'] = true;
            $response['message'] = 'Customer added successfully.';
        } else {
            $response['message'] = 'Failed to add customer.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
