<?php
    require '../config.php';
    require '../auth_check.php';

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
    
    $stmt = $pdo->prepare("
        SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS month, SUM(o.delivery_fee) AS total_income
        FROM assignments a
        JOIN orders o ON a.order_id = o.id
        WHERE a.raider_id = :raider_id 
        AND YEAR(o.created_at) = YEAR(CURRENT_DATE())
        GROUP BY month
        ORDER BY month
    ");
    $stmt->bindParam(':raider_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for Chart.js
    $months = [];
    $total_incomes = [];

    // Fill in months for the current year
    for ($i = 1; $i <= 12; $i++) {
        $months[] = date('Y-m', mktime(0, 0, 0, $i, 1));
        $total_incomes[] = 0; // Initialize with zero
    }

    foreach ($results as $row) {
        $monthIndex = date('n', strtotime($row['month'])) - 1; // Get the month index (0-11)
        $total_incomes[$monthIndex] = $row['total_income'];
    }

    // Prepare arrays for Chart.js
    $chart_months = $months;
    $chart_incomes = $total_incomes;
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="text-center p-4 text-sm bg-blue-500 text-white rounded">
                <h2 class="text-md">Total Assigned</h2>
                <h1 class="text-2xl"><?php echo $counts['assigned_assignments']; ?></h1>
            </div>
            <div class="text-center p-4 text-sm bg-green-500 text-white rounded">
                <h2 class="text-md">Total In-progress</h2>
                <h1 class="text-2xl"><?php echo $counts['in_progress_assignments']; ?></h1>
            </div>
            <div class="text-center p-4 text-sm bg-yellow-500 text-white rounded">
                <h2 class="text-md">Total Completed</h2>
                <h1 class="text-2xl"><?php echo $counts['completed_assignments']; ?></h1>
            </div>
        </div>
        <canvas id="myChart" width="400" height="200"></canvas>
        <canvas id="incomeChart" width="400" height="200"></canvas>
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
                        labels: ['Assigned', 'In-progress', 'Completed'],
                        datasets: [{
                            label: 'Orders',
                            data: [data.assigned_assignments, data.in_progress_assignments, data.completed_assignments],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    });
</script>

<script>
    const months = <?php echo json_encode($chart_months); ?>;
    const totalIncomes = <?php echo json_encode($chart_incomes); ?>;

    const ctx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(ctx, {
        type: 'bar', // or 'line', 'pie', etc.
        data: {
            labels: months,
            datasets: [{
                label: 'Total Income',
                data: totalIncomes,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include '../footer.php'; ?>