<?php
require 'connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        try {
            // Call the delete function
            deleteCustomer($id);
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No ID provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}
?>

