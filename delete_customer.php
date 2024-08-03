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

    // Delete Customer function
    function deleteCustomer($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Check if ID is set and is a valid integer
    if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT) !== false) {
        $id = intval($_POST['id']);
        error_log("Received ID: " . $id);

        // Call the deleteCustomer function
        if (deleteCustomer($id)) {
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
    }
} catch (PDOException $e) {
    // Log detailed error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: An unexpected error occurred.']);
}
?>
