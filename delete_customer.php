<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample', 'u510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
