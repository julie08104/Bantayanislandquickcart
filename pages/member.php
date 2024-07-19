<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="margin-left: 2px!important;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="m-0 text-dark">Customer List</h1>
                </div><!-- /.col -->
                <div class="col-md-6 mt-3">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Customer List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- .row -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box bg-danger mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total transaction</span>
                                    <span class="info-box-number">
                                        <?php 
                                            $stmt = $pdo->prepare("SELECT SUM(`total_buy`) FROM `member`");
                                            $stmt->execute();
                                            $res = $stmt->fetch(PDO::FETCH_NUM);
                                            echo $res[0];
                                        ?>
                                    </span>
                                </div>
                                <span class="info-box-icon"><i class="material-symbols-outlined">stacked_line_chart</i></span>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box bg-success mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total paid</span>
                                    <span class="info-box-number">
                                        <?php 
                                            $stmt = $pdo->prepare("SELECT SUM(`total_paid`) FROM `member`");
                                            $stmt->execute();
                                            $res = $stmt->fetch(PDO::FETCH_NUM);
                                            echo $res[0];
                                        ?>
                                    </span>
                                </div>
                                <span class="info-box-icon"><i class="material-symbols-outlined">paid</i></span>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="info-box bg-info mb-3">
                                <div class="info-box-content">
                                    <span class="info-box-text">Total due</span>
                                    <span class="info-box-number">
                                        <?php 
                                            $stmt = $pdo->prepare("SELECT SUM(`total_due`) FROM `member`");
                                            $stmt->execute();
                                            $res = $stmt->fetch(PDO::FETCH_NUM);
                                            echo $res[0];
                                        ?>
                                    </span>
                                </div>
                                <span class="info-box-icon"><i class="material-symbols-outlined">assignment_add</i></span>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- *************  table start here *********** -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>All Customer info</b></h3>
                    <button type="button" class="btn btn-primary btn-sm float-right rounded-0" data-toggle="modal" data-target=".myModal"><i class="fas fa-plus"></i> Add new</button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="empTable" class="display dataTable text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Company</th> <!-- Add this line -->
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Total Buy</th>
                                    <th>Total Paid</th>
                                    <th>Total Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $stmt = $pdo->prepare("SELECT * FROM `member`");
                                    $stmt->execute();
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['member_id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['company'] . "</td>"; // Ensure 'company' is fetched
                                        echo "<td>" . $row['address'] . "</td>";
                                        echo "<td>" . $row['con_num'] . "</td>";
                                        echo "<td>" . $row['total_buy'] . "</td>";
                                        echo "<td>" . $row['total_paid'] . "</td>";
                                        echo "<td>" . $row['total_due'] . "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

<!-- Initialize DataTables -->
<script>
        $(document).ready(function() {
    // Check if DataTables is already initialized on empTable
    if (!$.fn.dataTable.isDataTable('#empTable')) {
        $('#empTable').DataTable({
            columns: [
                { data: 'member_id' },
                { data: 'name' },
                { data: 'company' },
                { data: 'address' },
                { data: 'con_num' },
                { data: 'total_buy' },
                { data: 'total_paid' },
                { data: 'total_due' }
            ]
        });
    }
});
</script>

