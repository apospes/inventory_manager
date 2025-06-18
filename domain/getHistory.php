<?php
ob_start();
session_start();
require_once('../includes/helper_fun.inc.php');


check_session();
$barcode = intval($_GET['barcode']);
$monadaid = $_SESSION['id_monada'];

require('../database/mysqli_con.php');
if (!$dbcon) {
  die('Could not connect: ' . mysqli_error($dbcon));
}

try{
  $stmt = mysqli_prepare($dbcon, 'SELECT * FROM product_history_view WHERE barcode=? AND id_monada=?;');
  mysqli_stmt_bind_param($stmt, 'ii', $barcode, $monadaid);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  $count = mysqli_stmt_num_rows($stmt);
  if ($count<=1){
    print "<div class='text-danger'>Δεν βρέθηκε ιστορικό</div>";
  }else{

      print "<a class='btn btn-primary' href='product_history.php?barcode=$barcode'>Εμφάνιση</a>\n";
  }

  mysqli_stmt_close($stmt);
  mysqli_close($dbcon);
}catch (mysqli_sql_exception $e) {
  print "<div class='text-danger'>Κάτι πήγε στραβά!</div>";
}// try-catch




?>