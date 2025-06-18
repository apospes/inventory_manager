<?php
ob_start();
session_start();
require_once('../includes/helper_fun.inc.php');

check_session();
check_admin_users();
$id_users = intval($_GET['id_users']);
$action = intval($_GET['action']);

require('../database/mysqli_con.php');
if (!$dbcon) {
  die('Could not connect: ' . mysqli_error($dbcon));
}

try{
    if($action == 1){
        $stmt = mysqli_prepare($dbcon, 'UPDATE users SET energos=true WHERE id_users=?;');
        mysqli_stmt_bind_param($stmt, 'i', $id_users);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $msg = "Επιτυχής Αποδοχή";
    }else if($action == 0){
        $stmt = "DELETE FROM users WHERE id_users=$id_users;";
        mysqli_query($dbcon, $stmt);
        $msg = "Επιτυχής Απόρριψη";
    }else{
        //ob_end_flush();
        header("Location: index.php");
        exit();
    }
}catch (mysqli_sql_exception $e) {
    $msg = "Κάτι πήγε στραβά!";
}// try-catch

print "$msg";

mysqli_close($dbcon);


?>