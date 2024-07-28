<?php
/**
 * Description: The main class for Database.
 * Author: mayuri
 * Date Created: 2013
 * Revised By: 
 */

 //Database Constants
 defined('DB_SERVER')? null : define("DB_SERVER","127.0.0.1");//define our database server
 defined('DB_USER') ? null : define("DB_USER","u510162695_ample"); //define our database user
 defined('DB_PASS') ? null : define("DB_PASS","1Ample_database"); //define our database Password
 defined('DB_NAME') ? null : define("DB_NAME","u510162695_ample"); //define our database Name
 defined('DB_PORT') ? null : define("DB_PORT","3360"); // define our database port
 
 $thisfile = str_replace('\\','/'_FILE_);
 $docroot =$_SERVER['DOCUMENT_ROOT'];

 $webRoot = str_replace(array($docRoot, 'app/config.php'),'',$thisFile);
 $srvRoot = str_replace('config/config.php','',$thisFile);
 $connection = mysqli_connection(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);

 define('WEB_ROOT', $webRoot);
 define('SRV_ROOT', $srvRoot);
 ?>