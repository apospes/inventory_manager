<?php
ob_start();
session_start();
require_once('../includes/helper_fun.inc.php');

check_session();
$barcode = intval($_GET['barcode']);

require('../database/mysqli_con.php');
if (!$dbcon) {
  die('Could not connect: ' . mysqli_error($dbcon));
}

try{
  $stmt = mysqli_prepare($dbcon, 'CALL delete_product_change(true, ?);');
  mysqli_stmt_bind_param($stmt, 'i', $barcode);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);


  mysqli_stmt_close($stmt);
  mysqli_close($dbcon);
}catch (mysqli_sql_exception $e) {
  var_dump($e);
  $errorpage_url = "errorpage.php?e=" . urlencode($e);
  header("Location: $errorpage_url");
  exit();
}// try-catch

?>