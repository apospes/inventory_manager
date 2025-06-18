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
    $firstname = filter_input(INPUT_POST, 'firstname');
    $lastname = filter_input(INPUT_POST, 'lastname');
    $id_vathmos = filter_input(INPUT_POST, 'id_vathmos', FILTER_VALIDATE_INT);
    $id_oplo = filter_input(INPUT_POST, 'id_oplo', FILTER_VALIDATE_INT);
    $id_monada = filter_input(INPUT_POST, 'id_monada', FILTER_VALIDATE_INT);
    $username = filter_input(INPUT_POST, 'username');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
}else {
    call_error_page("Μη αποδεκτό αίτημα!");
}

try{
    if (empty($id_users) || empty($lastname) || empty($firstname) || 
    empty($id_vathmos) || empty($id_oplo) || empty($id_monada) || 
    empty($username) || empty($email))
    call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
    $query = "UPDATE users SET firstname=?, lastname=?, id_vathmos=?, id_oplo=?, 
                                id_monada=?, username=?, email=? WHERE id_users=?;";
    $stmt = mysqli_prepare($dbcon, $query);
    mysqli_stmt_bind_param($stmt, 'ssiiissi', $firstname, $lastname, $id_vathmos, 
                                            $id_oplo, $id_monada, $username, $email, $id_users);
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