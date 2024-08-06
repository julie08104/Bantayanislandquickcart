<?php
require_once 'app/init.php'; // Ensure this file connects to your database

try {
    // Check if ID is set
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        error_log("POST data received: " . print_r($_POST, true)); // Log the POST data
        error_log("Attempting to delete customer with ID: $id"); // Log the ID being deleted

        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            // Return an error response
            error_log("Error executing delete statement: " . implode(", ", $stmt->errorInfo())); // Log error info
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        error_log("No customer ID provided."); // Log when no ID is provided
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log database errors
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
