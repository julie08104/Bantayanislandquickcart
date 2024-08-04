<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if ID is set
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

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
