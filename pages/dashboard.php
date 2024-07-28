<?php
$servername = "127.0.0.1";
$username = "u510162695_ample";
$password = "1Ample_database";
$dbname = "u510162695_ample";

try {
   $pdo = new PDO("mysql:host=127.0.0.1;dbname=u510162695_ample", 'u510162695_ample', '1Ample_database');
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define the table and column names
    $tableName = 'your_table_name';
    $columnName = 'alert_quantity';

    // Check if the column exists
    $columnExistsQuery = "SELECT COUNT(*) AS count 
                          FROM information_schema.COLUMNS 
                          WHERE TABLE_SCHEMA = DATABASE() 
                          AND TABLE_NAME = '$tableName' 
                          AND COLUMN_NAME = '$columnName'";
    $statement = $pdo->query($columnExistsQuery);
    $result = $statement->fetch();

    // If column does not exist, add it
    if ($result['count'] == 0) {
        $addColumnQuery = "ALTER TABLE $tableName ADD $columnName INT(10) NOT NULL";
        $pdo->exec($addColumnQuery);
        echo "Column 'alert_quantity' added successfully.";
    } else {
        echo "";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
>
  <div class="content-wrapper" style="margin-left: 0px!important; margin-top: 0px!important; padding-left: 0px!important;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><!-- Dashboard v2 --></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-left">
              <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
              <!-- <li class="breadcrumb-item active">Dashboard</li> -->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="min-height:80vh;">
     <div  class="head">

      
      <div class="container-fluid">
        <!-- .row -->
        <div class="row">

          <div class="col-xl-3 col-xxl-6 col-sm-6">
            <div class="info-box bg-danger ">
              <div class="info-box-content">
                <span class="info-box-text">Total Costumer</span>
                <span class="info-box-number">
                <?php 
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM `customer`");
                            $stmt->execute();
                            $res = $stmt->fetch(PDO::FETCH_NUM);
                            echo $total_customers = $res[0];
                        ?>
                    </span>
                </span>
              </div>
              <span class="info-box-icon "><i class="material-symbols-outlined">
                 supervisor_account</i></span>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>


          <!-- /.col -->
          <div class="col-xl-3 col-xxl-6 col-sm-6">
            <div class="info-box  bg-success">
              <div class="info-box-content">
                <span class="info-box-text">Total Riders</span>
                <span class="info-box-number"> 
                  <?php 
                    echo $all_customer = $obj->total_count('riders');
                  ?>
                  </span>
              </div>
               <span class="info-box-icon elevation-1"><i class="fas fa-biking"></i></span>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <div class="col-xl-3 col-xxl-6 col-sm-6">
            <div class="info-box bg-info ">
             
              <div class="info-box-content">
                <span class="info-box-text"> Active Riders</span>
                <span class="info-box-number"> 
                    
                          <?php  
                      $stmt = $pdo->query('SELECT COUNT(*) AS total_active_riders FROM riders WHERE status = "active"');
                      $stmt->execute();
                      $res = $stmt->fetch(PDO::FETCH_NUM);
                      echo $total_active_riders =  $res[0];
                  ?>
                  </span>
              </div>
               <span class="info-box-icon elevation-1"><i class="fas fa-biking"></i></span>

              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

<!-- 
          <div class="col-xl-3 col-xxl-6 col-sm-6">
            <div class="info-box bg-secondary ">
              
              <div class="info-box-content">
                <span class="info-box-text">Total purchase</span>
                <span class="info-box-number"> 
                         <?php  
                      $stmt = $pdo->prepare("SELECT SUM(`purchase_subtotal`) FROM `purchase_products`");
                      $stmt->execute();
                      $res = $stmt->fetch(PDO::FETCH_NUM);
                      echo $total_purchase =  $res[0];
                  ?>
                  </span>
              </div>
              <span class="info-box-icon elevation-1"><i class="material-symbols-outlined">payments</i></span> -->

              <!-- /.info-box-content -->
            <!-- </div> -->
            <!-- /.info-box -->
          <!-- </div> -->
    

          <!-- fix for small devices only -->
        <!--   <div class="clearfix hidden-md-up"></div>

           <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending orders</span>
                <span class="info-box-number">760</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Incomplete Orders</span>
                <span class="info-box-number">2,000</span>
              </div>
            </div>
          </div> 
      
        </div>
      -->


       <!--  <div class="row">
          <div class=" col-md-6">
            <div class="info-box bg-cards-1">
              <div class="info-box-content  text-center text-white">
                <h2 class="info-box-text">Today</h2>
                <span class="sell">Sell:
                    <?php 
                      $today = date('Y-m-d');
                        $stmt = $pdo->prepare("SELECT SUM(`net_total`) FROM `invoice` WHERE `order_date` = '$today'");
                        $stmt->execute();
                        $res = $stmt->fetch(PDO::FETCH_NUM);
                        echo $res[0];

                        ?>
                     -->
              <!--   </span><br>
                <span class="buy">Buy:
                    <?php 
                      $today = date('Y-m-d');
                        $stmt = $pdo->prepare("SELECT SUM(`purchase_net_total`) FROM `purchase_products` WHERE `purchase_date` = '$today'");
                        $stmt->execute();
                        $res = $stmt->fetch(PDO::FETCH_NUM);
                        echo $res[0];

                        ?>
                </span>
              </div> -->
              <!-- /.info-box-content -->
            <!-- </div> -->
            <!-- /.info-box -->
          <!-- </div> -->
          <!-- /.col -->
    <!--       <div class=" col-md-6">
            <div class="info-box bg-cards-2">
              <div class="info-box-content  text-center">
                <h2 class="info-box-text">Monthly</h2>
                <span class="sell">Sell:
                  <?php 
                      $start_data = date('Y-m-01-');
                      $end_date =   date('Y-m-t');
                        $stmt = $pdo->prepare("SELECT SUM(`net_total`) FROM `invoice` WHERE `order_date` BETWEEN '$start_data' AND  '$end_date' ");
                        $stmt->execute();
                        $res = $stmt->fetch(PDO::FETCH_NUM);
                        echo $res[0];

                        ?>
                 </span><br>
                <span class="buy">Buy:
                  <?php 
                       $start_data = date('Y-m-01-');
                      $end_date =   date('Y-m-t');
                        $stmt = $pdo->prepare("SELECT SUM(`purchase_net_total`) FROM `purchase_products` WHERE `purchase_date` BETWEEN '$start_data' AND  '$end_date'");
                        $stmt->execute();
                        $res = $stmt->fetch(PDO::FETCH_NUM);
                        echo $res[0];

                        ?> -->
           <!--      </span>
              </div> -->
              <!-- /.info-box-content -->
            <!-- </div> -->
            <!-- /.info-box -->
 <?<?php
// Database connection credentials
$host = '127.0.0.1'; // e.g., 'localhost'
$dbname = 'u510162695_ample'; // e.g., 'my_database'
$username = 'u510162695_ample'; // e.g., 'root'
$password = '1Ample_database'; // e.g., 'password'

try {
 
    // Fetch total riders
    $stmt = $pdo->query("SELECT COUNT(*) AS total_riders FROM riders");
    $totalRiders = $stmt->fetch(PDO::FETCH_ASSOC)['total_riders'];

    // Fetch active riders (assuming 'status' column indicates active/inactive status)
    $stmt = $pdo->query("SELECT COUNT(*) AS active_riders FROM riders WHERE status = 'active'");
    $activeRiders = $stmt->fetch(PDO::FETCH_ASSOC)['active_riders'];

    // Fetch total customers
    $stmt = $pdo->query("SELECT COUNT(*) AS total_customers FROM customer");
    $totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

  <div class="container-fluid" >
         <canvas id="barChart" style="height:10%; max-width: auto!important; "></canvas>
         <br>
        <canvas id="pieChart" style="margin-top: 50px; height:10%; max-width: auto!important;"></canvas>
    </div>
  
    <script>
      const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Total Riders', 'Active Riders', 'Total Customers'],
                datasets: [{
                    label: 'Count',
                    data: [<?php echo $totalRiders; ?>, <?php echo $activeRiders; ?>, <?php echo $totalCustomers; ?>],
                    backgroundColor: [
                        'rgb(255, 99, 132)',   // solid red
                        'rgb(173, 216, 230)', // solid light blue
                        'rgb(255, 206, 86)'   // solid yellow
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',     // solid red
                        'rgb(173, 216, 230)',   // solid light blue
                        'rgb(255, 206, 86)'     // solid yellow
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

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Total Riders', 'Active Riders', 'Total Customers'],
                datasets: [{
                    label: 'Count',
                    data: [<?php echo $totalRiders; ?>, <?php echo $activeRiders; ?>, <?php echo $totalCustomers; ?>],
                    backgroundColor: [
                        'rgb(255, 99, 132)',   // solid red
                        'rgb(173, 216, 230)', // solid light blue
                        'rgb(255, 206, 86)'   // solid yellow
                    ],
                    borderColor: [
                        'rgb(255, 255, 255)', // white border for better visibility
                        'rgb(255, 255, 255)', // white border for better visibility
                        'rgb(255, 255, 255)'  // white border for better visibility
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>









          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>
          
        </div>
      
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>