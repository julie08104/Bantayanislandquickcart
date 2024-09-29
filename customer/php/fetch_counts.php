<?php
require '../../config.php';
require '../../auth_check.php';

// Fetch counts
$counts = [];

// Fetch total assignments count
$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM orders WHERE customer_id  = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$counts['total_orders'] = $stmt->fetchColumn();

// Fetch counts for each status
$stmtPending = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM orders o
    WHERE o.customer_id = :user_id AND o.status = 'pending'
");
$stmtPending->execute(['user_id' => $_SESSION['user_id']]);
$counts['pending_orders'] = $stmtPending->fetchColumn();

$stmtAssigned = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM orders o
    WHERE o.customer_id = :user_id AND o.status = 'assigned'
");
$stmtAssigned->execute(['user_id' => $_SESSION['user_id']]);
$counts['assigned_orders'] = $stmtAssigned->fetchColumn();

$stmtInProgress = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM orders o
    WHERE o.customer_id = :user_id AND o.status = 'in_progress'
");
$stmtInProgress->execute(['user_id' => $_SESSION['user_id']]);
$counts['in_progress_orders'] = $stmtInProgress->fetchColumn();

$stmtCompleted = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM orders o
    WHERE o.customer_id = :user_id AND o.status = 'completed'
");
$stmtCompleted->execute(['user_id' => $_SESSION['user_id']]);
$counts['completed_orders'] = $stmtCompleted->fetchColumn();

// Output JSON
echo json_encode($counts);
?>
