<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'create':
            createRider($_POST);
            break;
        case 'update':
            updateRider($_POST);
            break;
        case 'delete':
            if (deleteRider($_POST['rider_id'])) {
                echo json_encode(['success' => true, 'message' => 'Rider deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete rider']);
            }
            exit;
    }
    header('Location: index.php?buy_list'); // Redirect after action
    exit;
}

?>
