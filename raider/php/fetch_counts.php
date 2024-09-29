<?php
require '../../config.php';
require '../../auth_check.php';

// Fetch counts
$counts = [];

// Fetch total assignments count
$stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM assignments WHERE raider_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$counts['total_assignments'] = $stmt->fetchColumn();

// Fetch counts for each status
$stmtInProgress = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM assignments a
    JOIN orders o ON a.order_id = o.id 
    WHERE a.raider_id = :user_id AND o.status = 'in_progress'
");
$stmtInProgress->execute(['user_id' => $_SESSION['user_id']]);
$counts['in_progress_assignments'] = $stmtInProgress->fetchColumn();

$stmtCompleted = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM assignments a
    JOIN orders o ON a.order_id = o.id 
    WHERE a.raider_id = :user_id AND o.status = 'completed'
");
$stmtCompleted->execute(['user_id' => $_SESSION['user_id']]);
$counts['completed_assignments'] = $stmtCompleted->fetchColumn();

$stmtAssigned = $pdo->prepare("
    SELECT COUNT(*) AS count 
    FROM assignments a
    JOIN orders o ON a.order_id = o.id 
    WHERE a.raider_id = :user_id AND o.status = 'assigned'
");
$stmtAssigned->execute(['user_id' => $_SESSION['user_id']]);
$counts['assigned_assignments'] = $stmtAssigned->fetchColumn();

// Output JSON
echo json_encode($counts);
?>
