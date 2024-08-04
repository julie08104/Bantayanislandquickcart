<?php
include 'database_connection.php'; // Ensure this file sets up the $pdo variable correctly

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
    $lastname = isset($_POST['lastname']) ? filter_var($_POST['lastname'], FILTER_SANITIZE_STRING) : '';
    $address = isset($_POST['address']) ? filter_var($_POST['address'], FILTER_SANITIZE_STRING) : '';
    $contact = isset($_POST['contact']) ? filter_var($_POST['contact'], FILTER_SANITIZE_STRING) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $company = isset($_POST['company']) ? filter_var($_POST['company'], FILTER_SANITIZE_STRING) : null;

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO customers (name, lastname, address, contact, email, company) VALUES (?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $lastname, $address, $contact, $email, $company]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Customer added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add customer.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
