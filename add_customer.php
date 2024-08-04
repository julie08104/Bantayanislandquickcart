<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare("INSERT INTO customers (name, lastname, company, address, contact, email) VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $lastname, $company, $address, $contact, $email]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add customer.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
