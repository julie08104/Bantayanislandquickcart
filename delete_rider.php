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

        $stmt = $pdo->prepare("DELETE FROM riders WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Rider deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting rider.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No rider ID provided.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
