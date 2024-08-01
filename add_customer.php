<?php
// Include your database connection or initialization script
require_once '../init.php'; // Adjust path as necessary

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if required POST data is set
        if (!isset($_POST['name'], $_POST['lastname'], $_POST['company'], $_POST['address'], $_POST['contact'], $_POST['email'])) {
            throw new Exception('Missing POST data.');
        }

        // Get POST data
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $company = $_POST['company'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        // Print POST data for debugging
        error_log("Received POST data: name=$name, lastname=$lastname, company=$company, address=$address, contact=$contact, email=$email");

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
        // Log and display database-related errors
        error_log('PDOException: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Log and display general errors
        error_log('Exception: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
