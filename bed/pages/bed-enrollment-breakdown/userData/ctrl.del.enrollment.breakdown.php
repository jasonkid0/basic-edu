<?php require '../../../includes/conn.php';
session_start();

$date = $_GET['date'];
mysqli_query($conn, "DELETE FROM tbl_breakdown WHERE date = '$date'");
$_SESSION['success-del'] = true;
header('location: ../online_list.php');

?>