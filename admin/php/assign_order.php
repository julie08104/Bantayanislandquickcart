<?php
    require '../../config.php';

    $order_id = $_POST['order_id'];
    $raider_id = $_POST['raider_id'];

    $stmt = $pdo->prepare('UPDATE orders SET status = "assigned" WHERE id = ?');
    $stmt->execute([$order_id]);

    $stmt = $pdo->prepare('INSERT INTO assignments (order_id, raider_id, assigned_at) VALUES (?, ?, NOW())');
    $stmt->execute([$order_id, $raider_id]);
    
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Order assigned successfully!'];
    header("Location: ../order-list.php");
?>
