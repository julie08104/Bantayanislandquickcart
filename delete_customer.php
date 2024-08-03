<?php
header('Content-Type: application/json');

$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample';
$username = 'u510162695_ample';
$password = '1Ample_database';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        // Log or print the ID for debugging
        error_log("Received ID: " . $id);

        if ($id !== null && $id > 0) {
            $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Customer not found or already deleted.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid or missing customer ID.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request method or action.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
