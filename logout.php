<?php 
session_start();
$connection = mysqli_connect("localhost", "root", "", "data");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}   
session_unset();
session_destroy();
header("Location: log.php");
exit();
?>