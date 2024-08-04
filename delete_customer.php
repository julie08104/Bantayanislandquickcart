<?php
include 'database_connection.php'; // Ensure this includes your PDO setup

header('Content-Type: text/plain');

$response = ['success' => false, 'message' => ''];

try {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        $stmt = $pdo->prepare("DELETE FROM customer WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Customer deleted successfully.';
            echo 'success';
        } else {
            $response['message'] = 'Customer not found.';
            echo 'error';
        }
    } else {
        $response['message'] = 'Invalid request.';
        echo 'error';
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    echo 'error';
}
?>
