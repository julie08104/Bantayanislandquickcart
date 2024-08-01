<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include necessary files
require_once 'inc/header.php';
require_once 'inc/sidebar.php';
?>
<center>
<div class="content-wrapper">
  <?php 
  // Determine the page to load
  if (isset($_GET['page'])) {
    $page = 'pages/' . $_GET['page'] . '.php';
  } else {
    $page = 'pages/dashboard.php'; // Default page if no page parameter is set
  }
  
  // Check if the file exists before including
  if (file_exists($page)) {
    require_once $page; 
  } else {
    echo "Page not found: " . htmlspecialchars($page) . "<br>";
    require_once 'pages/error_page.php';
  }
  ?>
</div>
</center>
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>

<?php require_once 'inc/footer.php'; ?>
