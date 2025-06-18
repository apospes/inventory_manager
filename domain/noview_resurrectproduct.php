<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
if ($_SESSION['id_rolos'] != 2) call_errorpage('Σελίδα πρόσβασης μόνο για Γενικό Διαχειριστή Υλικού της Μονάδας!');

if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
    $barcode = filter_input(INPUT_POST, 'barcode', FILTER_VALIDATE_INT);
}else {
    call_error_page("Μη αποδεκτό αίτημα!");
}

try{
    if (empty($barcode)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
    $query = "CALL delete_product_change(false, $barcode)";
    $stmt = mysqli_prepare($dbcon, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($dbcon);
    //ob_end_flush();
    header("Location: deleted_product_view.php" );
    exit();
}catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}// try-catch

?>