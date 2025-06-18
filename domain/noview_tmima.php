<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');
define("CHANGE", 1);
define("DELETE", 2);
define("CREATE", 3);
require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
if ($_SESSION['id_rolos'] != 2) call_errorpage('Σελίδα πρόσβασης μόνο για Γενικό Διαχειριστή Υλικού της Μονάδας!');

try{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
        $action = filter_input(INPUT_POST, 'action', FILTER_VALIDATE_INT);
        if (empty($action)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
    
        if($action == CHANGE){
            $id_tmima = filter_input(INPUT_POST, 'id_tmima', FILTER_VALIDATE_INT);
            $tmima_name = filter_input(INPUT_POST, 'tmima_name');
            if (empty($id_tmima) || empty($tmima_name)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
            $query = "UPDATE tmima SET tmima_name = '$tmima_name' WHERE id_tmima=$id_tmima;";
            $stmt = mysqli_prepare($dbcon, $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            //ob_end_flush();
            header("Location: manage_tmimata.php" );
            exit();
        }else if ($action == DELETE){
            $id_tmima = filter_input(INPUT_POST, 'id_tmima', FILTER_VALIDATE_INT);
            if (empty($id_tmima)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
            $query = "DELETE FROM tmima WHERE id_tmima=$id_tmima;";
            $stmt = mysqli_prepare($dbcon, $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            //ob_end_flush();
            header("Location: manage_tmimata.php" );
            exit();
        }else if ($action == CREATE){
            $id_monada = filter_input(INPUT_POST, 'id_monada', FILTER_VALIDATE_INT);
            $tmima_name = filter_input(INPUT_POST, 'tmima_name');
            if (empty($id_monada) || empty($tmima_name)) call_errorpage('Κάτι πήγε στραβά με τα στοιχεία υποβολής!');
            $query = "INSERT INTO tmima(tmima_name, id_monada) VALUES('$tmima_name', $id_monada);";
            $stmt = mysqli_prepare($dbcon, $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            //ob_end_flush();
            header("Location: manage_tmimata.php" );
            exit();
        }// if - elseif - elseif (ΤΡΟΠΟΠΟΙΗΣΗ - ΔΙΑΓΡΑΦΗ - ΔΗΜΙΟΥΡΓΙΑ ΤΜΗΜΑΤΟΣ)
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