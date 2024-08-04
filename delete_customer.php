<?php
header('Content-Type: application/json');
include 'database_connection.php'; // Adjust path as necessary

$response = ['success' => false, 'message' => 'An error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id > 0) {
        try {
            // Try directly executing a delete query
            $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
            $success = $stmt->execute([$id]);

            if ($success) {
                $response = ['success' => true, 'message' => 'Customer deleted successfully.'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to delete customer.'];
            }
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    } else {
        $response = ['success' => false, 'message' => 'Invalid ID.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method.'];
}

echo json_encode($response);
?>
