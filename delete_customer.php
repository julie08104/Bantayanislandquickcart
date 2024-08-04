<?php
require '../init.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include 'database_connection.php'; // Adjust path as necessary

$response = ['success' => false, 'message' => 'An error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id > 0) {
        try {
            $success = deleteCustomer($id);
            
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
