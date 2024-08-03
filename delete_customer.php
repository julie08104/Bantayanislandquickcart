<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=u510162695_ample', 'u510162695_ample', '1Ample_database');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function deleteCustomer($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
    return $stmt->execute([$id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($action === 'delete' && $id > 0) {
        $success = deleteCustomer($id);
        echo json_encode(['success' => $success, 'message' => $success ? 'Customer deleted successfully.' : 'Failed to delete customer.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
}
?>
