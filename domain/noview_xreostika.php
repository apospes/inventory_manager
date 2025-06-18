<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');

require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
check_no_admin_users();

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

    $monada_pros = filter_input(INPUT_POST, 'monada_pros',FILTER_VALIDATE_INT);
    $monada_apo = $_SESSION['id_monada'];
    $aa = filter_input(INPUT_POST, 'aa',FILTER_VALIDATE_INT);
    $helptable = [] ;
    $barcodeget = [];
    $sum = 0;
    for ($i=1; $i<=$aa; $i++) {
        $help = "barcode_".$i;
        $helptable[$i] = filter_input(INPUT_POST, $help, FILTER_VALIDATE_INT);
        if (!empty($helptable[$i])){
            $sum = $sum + 1;
            $barcodeget[$sum] = $helptable[$i];
        }
    }


    // //ΕΩΣ ΕΔΩ ΕΧΟΥΝ ΤΑΞΙΔΕΨΕΙ ΟΛΑ ΤΑ BARCODE ΠΟΥ ΕΧΟΥΝ ΕΠΙΛΕΧΘΕΙ ΜΕΣΑ ΣΤΟΝ ΠΙΝΑΚΑ $barcodeget[] ΜΕ $sum ΣΤΟΙΧΕΙΑ
    // echo "<p> Τα barcode που έχουν ταξιδέψει είναι :\n";
    // for($i=1; $i<=$sum; $i++){
    //     echo "$barcodeget[$i]\n";
    // }
    // echo "apo:$monada_apo\t pros:$monada_pros\t</p>";




    //ΘΑ ΚΑΝΩ ΕΝΑ ΕΡΩΤΗΜΑ ΝΑ ΔΩ ΑΝ ΤΟ BARCODE ΥΠΑΡΧΕΙ ΣΤΟΝ ΠΙΝΑΚΑ, ΔΗΛΑΔΗ, ΕΚΚΡΕΜΕΙ ΧΡΕΩΣΗ ΚΑΙ ΘΑ ΤΟΝ ΑΝΑΚΑΤΕΥΘΥΝΩ ΣΕ ΣΕΛΙΔΑ ΣΦΑΛΜΑΤΟΣ
    //ΑΝ ΔΕΝ ΥΠΑΡΧΕΙ, ΤΟΤΕ ΘΑ ΜΠΟΡΕΙ ΝΑ ΣΥΝΕΧΙΣΕΙ
    try{
        for($i=1; $i<=$sum; $i++){
            $qinsertekkxreoseis = "SELECT * from ekkremeis_xreoseis where barcode=?";
            $stmtinsertekkxreoseis = mysqli_prepare($dbcon, $qinsertekkxreoseis);
            mysqli_stmt_bind_param($stmtinsertekkxreoseis, 'i', $barcodeget[$i]);
            mysqli_stmt_execute($stmtinsertekkxreoseis);
            mysqli_stmt_store_result($stmtinsertekkxreoseis);
            if (mysqli_stmt_num_rows($stmtinsertekkxreoseis) == 1 ) {
                mysqli_stmt_close($stmtinsertekkxreoseis);
                mysqli_close($dbcon);
                call_errorpage('Κάποιο/α από τα υλικά είναι σε αναμονή έγκρισης!');
                exit();
            }
            mysqli_stmt_close($stmtinsertekkxreoseis);
        }

    //ΤΟ ΔΕΥΤΕΡΟ ΒΗΜΑ ΘΑ ΕΙΝΑΙ Η ΕΙΣΟΔΟΣ ΤΩΝ ΤΙΜΩΝ ΣΤΟΝ ΠΙΝΑΚΑ ΧΩΡΙΣ ΦΟΒΟ ΓΙΑ ΔΙΠΛΟ ΕΓΓΡΑΦΕΣ
    
        for($i=1; $i<=$sum; $i++){
            $parat = filter_input(INPUT_POST, 'perigrafi');
            $parat = strip_tags($parat);
            $user = $_SESSION['id_user'];
            $qinsertekkxreoseis = "INSERT INTO ekkremeis_xreoseis (barcode, id_monada_before, id_monada_after, id_users, perigrafh_xreosis) VALUES (?,?,?,?,?)";
            $stmtinsertekkxreoseis = mysqli_prepare($dbcon, $qinsertekkxreoseis);
            mysqli_stmt_bind_param($stmtinsertekkxreoseis, 'iiiis', $barcodeget[$i], $monada_apo , $monada_pros, $user, $parat);
            mysqli_stmt_execute($stmtinsertekkxreoseis);
            mysqli_stmt_close($stmtinsertekkxreoseis);
        }

        mysqli_close($dbcon);
        header("Location: prosproothisi.php");//ΘΑ ΤΟΝ ΠΑΩ ΣΤΙΣ ΑΝΑΜΟΝΕΣ
        exit();
    } catch (mysqli_sql_exception $e) {
        var_dump($e);
        $errorpage_url = "errorpage.php?e=" . urlencode($e);
        header("Location: $errorpage_url");
        exit();
    }

} else {
    call_error_page("Σφάλμα: Μη αποδεκτό αίτημα, λάθος κλήση της σελίδας!");
}

mysqli_close($dbcon);
//ob_end_flush();
?>