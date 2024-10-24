<?php

require '../../config.php';
require '../../auth_check.php';

try {
    $stmt = $pdo->prepare("UPDATE assignments SET latitude = :latitude, longitude = :longitude WHERE order_id = :order_id AND raider_id = :raider_id");
    $stmt->bindParam(':latitude', $_POST['latitude']);
    $stmt->bindParam(':longitude', $_POST['longitude']);
    $stmt->bindParam(':order_id', $_POST['order_id']);
    $stmt->bindParam(':raider_id', $_POST['raider_id']);
    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
