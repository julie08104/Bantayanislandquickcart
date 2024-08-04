<?php
// Include your database connection or initialization script
require_once '../init.php'; // Adjust path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get POST data
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO customers (name, lastname, company, address, contact, email) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Execute the statement
        $stmt->execute([$name, $lastname, $company, $address, $contact, $email]);

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
