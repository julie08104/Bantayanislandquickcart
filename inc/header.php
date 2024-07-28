<?php
require_once 'app/init.php';
if ($Ouser->is_login() == false) {
  header("location:login.php");
}
$actual_link = explode('=', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
  $actual_link = end($actual_link);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
 <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Quickcart Door-to-Door Online Shopping</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="assets/css/style.css" type='text/css' />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />

  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  
  <!-- DataTables CSS -->
 
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <!-- DataTables JS -->
    <!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script> -->

  <!-- datepi cker css  -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <!-- select 2 css  -->
  <link rel="stylesheet" type="text/css" href="plugins/select2/css/select2.min.css"/>
  <!-- custom css  -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- Google Font: Source Sans Pro -->
<!-- date picker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> -->
  <!-- Include FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   

  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"><!-- 
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"> -->
 <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- DataTables CSS -->
       <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    
   








    .hide {
            display: none;
        }


.btn-group .btn {
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-group .btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.btn-group .btn:active {
    transform: scale(0.95);
    box-shadow: none;
}
 /* Custom styles to adjust table size */
    .modal-body table {
        font-size: 1.1em; /* Increase font size */
    }
    .modal-body th, .modal-body td {
        padding: 10px; /* Increase padding */
    }
     .modal-body {
        padding: 20px; /* Add some padding */
    }
.nav-sidebar .nav-item .nav-link {
        color: #333;
        transition: background-color 0.3s, color 0.3s;
    }

    .nav-sidebar .nav-item .nav-link:hover {
        background-color: #007bff;
        color: #fff;
    }

    .nav-sidebar .nav-item .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }

    .nav-sidebar .nav-item .nav-link i {
        margin-right: 10px;
    }

    .nav-sidebar .nav-item .nav-link p {
        display: inline;
        margin: 0;
    }
  
        @media print {
            .no-print {
                display: none !important;
            }
        }

        /* Custom CSS for table styling */
       table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
}

th, td {
    padding: 12px 15px;
    border: 1px solid gray; /* Gray color for row and column lines */
}

th {
    background-color: #C0C0C0; /* Silver color for table headers */
    color: black;
}

tr:nth-of-type(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Sticky header for better usability */
thead th {
    position: sticky;
    top: 0;
    z-index: 1;
    background-color: #C0C0C0; /* Silver color for sticky headers */
    color: black;
}

/* Hover effect for table rows */
tbody tr:hover td {
    background-color: #f1f1f1;
}

/* Container max width and responsive design */
.container {
    max-width: 1200px;
}

/* Horizontal scroll for small screens */
.table-container {
    overflow-x: auto;
}

/* Responsive table styling for smaller screens */
@media screen and (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }

    thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    tr {
        border: 1px solid gray;
        margin-bottom: 5px;
    }

    td {
        border: none;
        border-bottom: 1px solid gray;
        position: relative;
        padding-left: 50%;
        text-align: right;
    }

    td:before {
        position: absolute;
        top: 12px;
        left: 15px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: bold;
    }

    /* Adjust column widths and labels for different table data */
    td:nth-of-type(1):before { content: "ID"; }
    td:nth-of-type(2):before { content: "Name"; }
    td:nth-of-type(3):before { content: "Last Name"; }
    td:nth-of-type(4):before { content: "Gender"; }
    td:nth-of-type(5):before { content: "Address"; }
    td:nth-of-type(6):before { content: "Contact Number"; }
    td:nth-of-type(7):before { content: "Email"; }
    td:nth-of-type(8):before { content: "Vehicle Type"; }
    td:nth-of-type(9):before { content: "License Number"; }
    td:nth-of-type(10):before { content: "Status"; }
    td:nth-of-type(11):before { content: "Date Joined"; }
    td:nth-of-type(12):before { content: "Total Rides"; }
    td:nth-of-type(13):before { content: "Rating"; }
    td:nth-of-type(14):before { content: "Payment Method"; }
}

@media screen and (max-width: 480px) {
    td {
        padding-left: 40%;
    }

    td:before {
        left: 10px;
        width: 35%;
    }
}

@media screen and (max-width: 360px) {
    td {
        padding-left: 30%;
    }

    td:before {
        left: 5px;
        width: 25%;
    }
}
 .chart-container {
            width: 33%;
            max-width: 0px;
            margin: 10 auto;
            padding: 20px;
        }
        /* Canvas element */
        canvas {
            width: 40% !important;
            height: auto !important;
        }
         body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

    </style>
</head>


<div class="wrapper">
   <!-- Page Preloder -->
   <div id="page"  style="display: none;"></div>
    <!-- <div id="loading"></div> -->
  <!-- Navbar -->

  <nav class="main-header navbar navbar-expand navbar-silver navbar-light" >

    <!-- Left navbar links -->
    <ul class="navbar-nav">

      <li class="nav-item">

      </li>
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
 <h3> Dashboard </h3>   
    </ul>

 <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <div id=""></div>
      <div class="notice">
        <br><br>
            <span><strong>Administration</strong></span>      
      </div>
                    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
          <img src="assets/images/admin.png">
           
        </a>
        <div class="dropdown-menu dropdown-menu-right p-0">
          <a href="index.php?page=profile" class="dropdown-item p-1">
            <i class="material-symbols-outlined">person</i> Profile
          </a>

        <!--   <a href="index.php?page=profile" class="dropdown-item p-1">
            <i class="material-symbols-outlined">
            stacked_inbox</i>Inbox
          </a> -->

          <a href="#" class="dropdown-item pic p-1" onclick="confirmLogout()">
              <i class="material-symbols-outlined">logout</i> Logout
          </a>



        </div>
      </li>

    </ul>
  </nav>
  <!-- Place this at the end of your body -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var loader = document.getElementById('loading');
    if (loader) {
        loader.style.display = 'none';
    }

    var pageContent = document.getElementById('page');
    if (pageContent) {
        pageContent.style.display = 'block';
    }
});

 $(document).ready(function() {
        $('#toggleSidebar').click(function() {
            $('aside.main-sidebar').toggleClass('sidebar-mini');
            $('.main-content').toggleClass('content-mini');
        });
    });
</script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- DataTables JS -->
<!-- <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
 -->

  <script>
   $(document).ready(function() {
        $('#suppliar_table').DataTable();

        // Edit button functionality
        $('.edit-btn').on('click', function() {
            $('#edit-id').val($(this).data('id'));
            $('#edit-name').val($(this).data('name'));
            $('#edit-company').val($(this).data('company'));
            $('#edit-address').val($(this).data('address'));
            $('#edit-con_num').val($(this).data('con_num'));
            $('#edit-email').val($(this).data('email'));
        });
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout script
                window.location.href = 'app/action/logout.php';
            }
        });
    }
</script>

  <!-- /.navbar -->