<?php
header('Content-Type: application/json');

// Database connection
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample', 'u510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if ID is provided
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // Debugging: Log ID to check if it's correctly received
        file_put_contents('log.txt', "ID received: " . $id . "\n", FILE_APPEND);

        // Prepare and execute delete statement
        try {
            $stmt = $pdo->prepare("DELETE FROM customer WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    // Successful deletion
                    echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
                } else {
                    // ID not found
                    echo json_encode(['success' => false, 'message' => 'Customer not found.']);
                }
            } else {
                // Error executing delete statement
                echo json_encode(['success' => false, 'message' => 'Error executing the delete statement.']);
            }
        } catch (PDOException $e) {
            // Database error during delete operation
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // No ID provided
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
