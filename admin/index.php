<?php
    require '../config.php';
    require '../auth_check.php';

    // Fetch counts
    $counts = [];

    // Fetch customers count
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM customers WHERE is_verified = 1");
    $counts['customers'] = $stmt->fetchColumn();

    // Fetch raiders count
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM raiders WHERE is_verified = 1");
    $counts['raiders'] = $stmt->fetchColumn();

    // Fetch users count
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM users");
    $counts['users'] = $stmt->fetchColumn();
?>

<?php include '../header.php'; ?>

<?php include 'sidebar.php'; ?>

<div class="p-4 sm:ml-64">
    <div class="bg-white shadow rounded p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="text-center p-4 text-sm bg-blue-500 text-white rounded">
                <h2 class="text-md">Total Customers</h2>
                <h1 class="text-2xl"><?php echo $counts['customers']; ?></h1>
            </div>
            <div class="text-center p-4 text-sm bg-green-500 text-white rounded">
                <h2 class="text-md">Total Raiders</h2>
                <h1 class="text-2xl"><?php echo $counts['raiders']; ?></h1>
            </div>
            <div class="text-center p-4 text-sm bg-yellow-500 text-white rounded">
                <h2 class="text-md">Total Users</h2>
                <h1 class="text-2xl"><?php echo $counts['users']; ?></h1>
            </div>
        </div>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('php/fetch_counts.php')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('myChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Customers', 'Raiders', 'Users'],
                        datasets: [{
                            label: 'Total Count',
                            data: [data.customers, data.raiders, data.users],
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
            });
    });
</script>

<?php include '../footer.php'; ?>