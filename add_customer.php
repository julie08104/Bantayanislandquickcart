<?php
include 'database_connection.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $company = !empty($_POST['company']) ? filter_var($_POST['company'], FILTER_SANITIZE_STRING) : null;

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
