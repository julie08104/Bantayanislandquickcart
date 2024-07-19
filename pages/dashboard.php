<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ample";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add the 'alert_quantity' column to the 'products' table
    $sql = "ALTER TABLE products ADD COLUMN alert_quantity INT";
    $pdo->exec($sql);
    
    echo "Column 'alert_quantity' added successfully to products table";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

  <div class="content-wrapper" style="margin-left: 0px!important; margin-top: 0px!important;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><!-- Dashboard v2 --></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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
               <span class="info-box-icon elevation-1"><i class="material-symbols-outlined">group</i></span>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <div class="col-xl-3 col-xxl-6 col-sm-6">
            <div class="info-box bg-info ">
             
              <div class="info-box-content">
                <span class="info-box-text">Total sells</span>
                <span class="info-box-number"> 
                    
                          <?php  
                      $stmt = $pdo->prepare("SELECT SUM(`sub_total`) FROM `invoice`");
                      $stmt->execute();
                      $res = $stmt->fetch(PDO::FETCH_NUM);
                      echo $total_sell_amount =  $res[0];
                  ?>
                  </span>
              </div>
               <span class="info-box-icon elevation-1"><i class="material-symbols-outlined">sell</i></span>

              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>


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
              <span class="info-box-icon elevation-1"><i class="material-symbols-outlined">payments</i></span>

              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
    

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
          <!-- </div> -->
          <!-- /.col -->
         
          
          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>
          
        </div>
      
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>