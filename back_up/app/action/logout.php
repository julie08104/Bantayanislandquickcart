 <?php
session_start();
session_unset();
session_destroy();
header("Location: ../../login.php");
exit();

	require_once '../init.php';
	$Ouser->logOut();
 ?>