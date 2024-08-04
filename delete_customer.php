<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Include your database connection file

function deleteCustomer($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        if ($stmt->execute([$id])) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log('SQL Error: ' . print_r($errorInfo, true)); // Log the error
            return false;
        }
    } catch (PDOException $e) {
        error_log('Database Error: ' . $e->getMessage());
        return false;
    }
}

$id = $_POST['id'] ?? '';

if ($id) {
    if (deleteCustomer($id)) {
        echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete customer.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
}
?>
