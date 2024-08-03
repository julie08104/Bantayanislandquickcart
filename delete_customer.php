<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

try {
    // Establishing the database connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID is set and is a valid integer
    if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
        $id = intval($_POST['id']);
        
        // Log ID for debugging
        error_log("Attempting to delete customer with ID: " . $id);

        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Check if any rows were affected
            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No customer found with the provided ID.']);
            }
        } else {
            error_log("Execute failed: " . implode(" ", $stmt->errorInfo()));
            echo json_encode(['success' => false, 'message' => 'Error executing delete operation.']);
        }
    } else {
        error_log('Invalid or missing customer ID: ' . (isset($_POST['id']) ? $_POST['id'] : 'none'));
        echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
    }
} catch (PDOException $e) {
    // Log detailed error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: An unexpected error occurred.']);
}
?>

