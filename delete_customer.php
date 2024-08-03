<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample'; // Update with your database name
$username = 'u510162695_ample'; // Update with your username
$password = '1Ample_database'; // Update with your password

try {
    // Establishing the database connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID is set
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // For debugging: log the ID to ensure it's correct
        error_log("Attempting to delete customer with ID: " . $id);

        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query and check if the customer was deleted
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No customer found with the provided ID.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error executing delete operation.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    // Log detailed error message for debugging but avoid exposing sensitive info to users
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: An unexpected error occurred.']);
}
?>

