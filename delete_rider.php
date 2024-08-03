<?php
header('Content-Type: application/json'); // Ensure JSON content type

// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

try {
    // Establishing the database connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID is set and is a valid integer
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id !== null && $id > 0) {
            error_log("Received ID: " . $id);

            // Prepare the delete statement
            $stmt = $pdo->prepare("DELETE FROM `riders` WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Check if a row was actually deleted
                if ($stmt->rowCount() > 0) {
                    // Return a success response
                    echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
                } else {
                    // No row was deleted
                    echo json_encode(['success' => false, 'message' => 'Customer not found or already deleted.']);
                }
            } else {
                // Output detailed error information
                error_log("Error executing DELETE statement: " . implode(" | ", $stmt->errorInfo()));
                echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    }
} catch (PDOException $e) {
    // Log detailed error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: An unexpected error occurred.']);
}
?>

