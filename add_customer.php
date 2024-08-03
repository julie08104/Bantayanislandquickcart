<?php
// Include your database connection or initialization script
require_once '../init.php'; // Adjust path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get POST data and sanitize
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
        $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
            exit;
        }

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO customers (name, lastname, address, contact, email) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Execute the statement
        $stmt->execute([$name, $lastname, $address, $contact, $email]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
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
