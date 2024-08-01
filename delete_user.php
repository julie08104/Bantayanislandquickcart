<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo 'Current Working Directory: ' . getcwd() . '<br>';
echo 'Resolved Path: ' . dirname(__DIR__) . '/init.php' . '<br>';

require_once dirname(__DIR__) . '/init.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo 'POST Data: ';
    print_r($_POST); // Output POST data for debugging

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        // Validate ID (ensure it's an integer)
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            try {
                $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'User deleted successfully';
                } else {
                    $response['message'] = 'Failed to delete user';
                }
            } catch (PDOException $e) {
                $response['message'] = 'Database error: ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'Invalid ID format';
        }
    } else {
        $response['message'] = 'ID not provided';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>
