<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}else if($_SESSION['user_type'] !== $page_type) {
    header("Location: /".$_SESSION['user_type']."/index.php");
    exit();
}
?>
