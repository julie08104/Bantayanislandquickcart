<?php
require '../init.php'; // Ensure this file includes your database connection setup

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    if (!empty($id)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM costumer WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Customer not found or already deleted.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error deleting customer: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid customer ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
