<?php
header('Content-Type: application/json'); // Ensure the content type is JSON

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample', 'u510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit();
}

function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
    if ($stmt->execute([$id])) {
        return true;
    } else {
        $errorInfo = $stmt->errorInfo();
        error_log('SQL Error: ' . print_r($errorInfo, true)); // Log the error
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $success = deleteCustomer($id);
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Customer deleted successfully.' : 'Failed to delete customer.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid ID.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
