<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');

require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
check_no_admin_users();
try{
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $csvFile = $_FILES['csv_file']['tmp_name'];



    if (($handle = fopen( $csvFile, 'r')) !== FALSE) {      
          fgetcsv($handle, 1000, ',');

        while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
            // Διάβασμα των δεδομένων από το αρχείο CSV
            list($id_tmima, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date) = $data;

            // Καθαρισμός δεδομένων και υποβολή ελέγχων
            $id_tmima = intval($id_tmima);
                $q="SELECT count(*) from tmima where id_tmima=? and id_monada=?;";
                $stmt = mysqli_prepare($dbcon, $q);
                mysqli_stmt_bind_param($stmt, 'ii', $id_tmima, $_SESSION['id_monada']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $count);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                if ($count==0) {continue;}


            
            $id_katigoria_prod = intval($id_katigoria_prod);
                $q="SELECT count(*) from katigoria_prod where id_katigoria_prod=?;";
                $stmt = mysqli_prepare($dbcon, $q);
                mysqli_stmt_bind_param($stmt, 'i', $id_katigoria_prod);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $count);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                if ($count==0) {continue;}


            
            $id_kataskeuastis = intval($id_kataskeuastis);
                $q="SELECT count(*) from kataskeuastis where id_kataskeuastis=?;";
                $stmt = mysqli_prepare($dbcon, $q);
                mysqli_stmt_bind_param($stmt, 'i', $id_kataskeuastis);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $count);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                if ($count==0) {continue;}


            
            $id_diktyo = intval($id_diktyo);
                if ($id_diktyo !=1 && $id_diktyo !=2 && $id_diktyo!=3) {continue;}
            
            $typos = strip_tags($typos);
            
            
            $merida = strip_tags($merida);
            

            $paratiriseis = strip_tags($paratiriseis);
            
            
            $leitourgiko = intval($leitourgiko);
                if ($leiourgiko !=0 && $leitourgiko !=1) {continue;}
            
            $xreomeno = intval($xreomeno);
                if ($xreomeno !=0 && $xreomeno !=1) {continue;}
            
            $sn = strip_tags($sn);
            
            $import_date = strip_tags($import_date);
                //if (!checkdate($mport_date)) {continue;}


            // Εισαγωγή δεδομένων στη βάση
            $qmassupload = "INSERT INTO products (id_tmima, id_katigoria_prod, id_kataskeuastis, id_diktyo, typos, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmtmassupload = mysqli_prepare($dbcon, $qmassupload)) {
                mysqli_stmt_bind_param($stmtmassupload, 'iiiisssiiss', $id_tmima, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date);
                mysqli_stmt_execute($stmtmassupload);
                mysqli_stmt_close($stmtmassupload);
            } else {
                $e = mysqli_error($dbcon);
                call_error_page($e);
            }
        }
        fclose($handle);
        header("Location: index.php");
        exit();
    } else {
        call_error_page("Σφάλμα: Αποτυχία ανοίγματος του αρχείου.");
    }
} else {
    call_error_page("Σφάλμα: Μη αποδεκτό αίτημα.");
}

} catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}

mysqli_close($dbcon);
//ob_end_flush();
?>
