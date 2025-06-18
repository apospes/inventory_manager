<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
check_admin_users();

try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
        $id_users = filter_input(INPUT_POST, 'id_users', FILTER_VALIDATE_INT);
        if (empty($id_users)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
        $query = "DELETE FROM users WHERE id_users=$id_users;";
        $stmt = mysqli_prepare($dbcon, $query);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbcon);
        //ob_end_flush();
        header("Location: manage_users.php" );
        exit();
    } else {
        call_error_page("Σφάλμα: Μη αποδεκτό αίτημα!");
    }//POST METHOD ONLY
    
    
    mysqli_close($dbcon);
    //ob_end_flush();
    exit();
}catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}// try-catch


?>