<?php
// Debugging lines
echo 'Current Working Directory: ' . getcwd() . '<br>';
echo 'Resolved Path: ' . dirname(__DIR__) . '/init.php' . '<br>';

// Corrected path to init.php
require_once dirname(__DIR__) . '/init.php';

// Your delete logic here
// Example:
$id = $_POST['id'];
if ($id) {
    // Your database connection and deletion logic here
    // Example:
    try {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting user: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
}
?>
