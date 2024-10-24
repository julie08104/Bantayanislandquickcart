<?php

require '../../config.php';
require '../../auth_check.php';

try {
    // Prepare the SQL statement
    $stmt = $pdo->prepare("SELECT latitude, longitude FROM assignments WHERE order_id = :order_id AND raider_id = :raider_id");
    
    // Bind parameters
    $stmt->bindParam(':order_id', $_GET['order_id'], PDO::PARAM_INT);
    $stmt->bindParam(':raider_id', $_GET['raider_id'], PDO::PARAM_INT);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch the location
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($location) {
        echo json_encode($location);
    } else {
        echo json_encode(['error' => 'Raider not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
