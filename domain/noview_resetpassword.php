<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
check_admin_users();

if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
    $id_users = filter_input(INPUT_POST, 'id_users', FILTER_VALIDATE_INT);
}else {
    call_error_page("Μη αποδεκτό αίτημα!");
}

try{
    if (empty($id_users)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
    $hash = password_hash('123', PASSWORD_DEFAULT);
    $query = "UPDATE users SET user_password=? WHERE id_users=?";
    $stmt = mysqli_prepare($dbcon, $query);
    mysqli_stmt_bind_param($stmt, 'si', $hash, $id_users);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($dbcon);
    //ob_end_flush();
    header("Location: manage_users.php" );
    exit();
}catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}// try-catch

?>