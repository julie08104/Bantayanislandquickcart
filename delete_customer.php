<?php
// Database connection details
$dsn = 'mysql:host=127.0.0.1;dbname=u510162695_ample'; 
$username = 'u510162695_ample'; 
$password = '1Ample_database'; 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        error_log("Deleting customer with ID: $id"); // Log for debugging

        // Prepare and execute the delete statement
        $stmt = $pdo->prepare("DELETE FROM `customer` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully.']);
        } else {
            error_log("Delete failed: " . implode(", ", $stmt->errorInfo())); // Log error info
            echo json_encode(['success' => false, 'message' => 'Error deleting customer.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No customer ID provided.']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log database errors
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
