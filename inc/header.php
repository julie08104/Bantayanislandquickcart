<?php
require_once 'app/init.php';

// Check if user is logged in
if (!$Ouser->is_login()) {
    header("location:login.php");
    exit();
}

// Get the actual page being requested
$actual_link = explode('=', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$actual_link = end($actual_link);

// Debugging output
echo 'Actual Link: ' . htmlspecialchars($actual_link) . '<br>';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  
  <title>Quickcart Door-to-Door Online Shopping</title>

  <!-- Include necessary CSS files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

  <style>
    /* Custom CSS styles */
    /* ... (your existing custom styles) ... */
  </style>
</head>
<body>
<div class="wrapper">
  <div id="page" style="display: none;"></div>
  
  <nav class="main-header navbar navbar-expand navbar-silver navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        <h3> Dashboard </h3>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <div class="notice">
        <span><strong>Administration</strong></span>
      </div>
                    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
          <img src="assets/images/images2.png">
        </a>
        <div class="dropdown-menu dropdown-menu-right p-0">
          <a href="index.php?page=profile" class="dropdown-item p-1">
            <i class="material-symbols-outlined">person</i> Profile
          </a>
          <a href="#" class="dropdown-item pic p-1" onclick="confirmLogout()">
            <i class="material-symbols-outlined">logout</i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
</div>

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

  $('#suppliar_table').DataTable();

  $('.edit-btn').on('click', function() {
    $('#edit-id').val($(this).data('id'));
    $('#edit-name').val($(this).data('name'));
    $('#edit-company').val($(this).data('company'));
    $('#edit-address').val($(this).data('address'));
    $('#edit-con_num').val($(this).data('con_num'));
    $('#edit-email').val($(this).data('email'));
  });
});

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
      window.location.href = 'app/action/logout.php';
    }
  });
}
</script>

<!-- Include necessary JS files -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
