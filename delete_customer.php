<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample'; // Update with your database name
$username = 'u510162695_ample'; // Update with your username
$password = '1Ample_database'; // Update with your password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID is set
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        // Debugging: Log the ID to ensure it's correct
        error_log("Deleting customer with ID: " . $id);

        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            // Return an error response
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
