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
        try {
            // Prepare the delete statement
            $stmt = $pdo->prepare("DELETE FROM `riders` WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Check if a row was actually deleted
                if ($stmt->rowCount() > 0) {
                    return ['success' => true, 'message' => 'Customer deleted successfully.'];
                } else {
                    return ['success' => false, 'message' => 'Customer not found or already deleted.'];
                }
            } else {
                // Output detailed error information
                return ['success' => false, 'message' => 'Error deleting customer.'];
            }
        } catch (PDOException $e) {
            // Log detailed error message
            error_log("Database error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error: An unexpected error occurred.'];
        }
    }

    // Check if action is set and handle accordingly
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'delete':
                if (isset($_POST['id']) && intval($_POST['id']) > 0) {
                    $id = intval($_POST['id']);
                    $response = deleteCustomer($pdo, $id);
                    echo json_encode($response);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
                }
                break;

            // Add other cases (create, read, update) as needed

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action.']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method or action.']);
    }
} catch (PDOException $e) {
    // Log detailed error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: An unexpected error occurred.']);
}
?>
