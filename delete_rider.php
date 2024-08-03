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

    // Function to delete a customer by ID
    function deleteCustomer($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Return the number of affected rows
    }

    // Check if ID is set and is a valid integer
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        if ($id !== null) {
            error_log("Received ID: " . $id);

            // Call the deleteCustomer function
            $rowCount = deleteCustomer($pdo, $id);
            if ($rowCount > 0) {
                echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Customer not found or already deleted.']);
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
