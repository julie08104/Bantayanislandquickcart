<?php
    require '../config.php';
    require '../auth_check.php';

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
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="text-center p-4 text-sm bg-blue-500 text-white rounded">
                <h2 class="text-md">Total Pending</h2>
                <h1 class="text-2xl"><?php echo $counts['pending_orders']; ?></h1>
            </div>
            <div class="text-center p-4 text-sm bg-green-500 text-white rounded">
                <h2 class="text-md">Total In-progress</h2>
                <h1 class="text-2xl"><?php echo $counts['in_progress_orders']; ?></h1>
            </div>
            <div class="text-center p-4 text-sm bg-yellow-500 text-white rounded">
                <h2 class="text-md">Total Completed</h2>
                <h1 class="text-2xl"><?php echo $counts['completed_orders']; ?></h1>
            </div>
        </div>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('php/fetch_counts.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const ctx = document.getElementById('myChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Pending', 'In-progress', 'Completed'],
                        datasets: [{
                            label: 'Orders',
                            data: [data.pending_orders, data.in_progress_orders, data.completed_orders],
                            backgroundColor: [
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1,
                                    callback: function(value) {
                                        return value.toFixed(0);
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>

<?php include '../footer.php'; ?>