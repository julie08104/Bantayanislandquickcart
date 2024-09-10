<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require 'database.php'; // Adjust path if necessary

$response = ['success' => false, 'message' => 'An error occurred'];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    if (deleteCustomer($id)) {
        $response['success'] = true;
        $response['message'] = 'Customer deleted successfully.';
    } else {
        $response['message'] = 'Failed to delete customer.';
    }
} else {
    $response['message'] = 'No ID provided.';
}

echo json_encode($response);
?>
