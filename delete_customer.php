<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample', 'u510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        file_put_contents('log.txt', "ID received: " . $id . "\n", FILE_APPEND);

        try {
            $stmt = $pdo->prepare("DELETE FROM customer WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Customer not found.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error executing the delete statement.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
