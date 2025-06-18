<?php
ob_start();
session_start();
date_default_timezone_set('Europe/Athens');

require('../database/mysqli_con.php'); // Σύνδεση με τη βάση
require_once('../includes/helper_fun.inc.php');
check_session();
check_no_admin_users();

// ΑΠΟ ΤΗΝ ΣΕΛΙΔΑ ΠΡΟΣ ΠΑΡΑΛΑΒΗ ΚΑΤΕΥΘΥΝΟΜΑΣΤΕ ΕΔΩ.
// ΕΔΩ ΓΙΝΕΤΑΙ Η ΑΠΟΔΟΧΗ Ή Η ΑΠΟΡΡΙΨΗ ΤΟΥ ΑΙΤΗΜΑΤΟΣ.
// ΑΠΟ ΤΗΝ ΣΕΛΙΔΑ ΠΟΥ ΕΡΧΕΤΑΙ, ΕΧΕΙ ΠΙΛΕΞΕΙ ΥΛΙΚΑ ΑΠΟ ΠΙΝΑΚΕΣ ΟΙ ΟΠΟΙΟΙ ΕΙΝΑΙ ΔΙΑΦΟΡΕΤΙΚΟΙ ΓΙΑ ΚΑΘΕ ΜΟΝΑΔΑ
// ΤΟ ΠΕΡΙΕΡΓΟ ΗΤΑΝ ΠΩΣ ΤΑΞΙΔΕΥΑΝ ΤΑ ΥΛΙΚΑ ΜΟΝΟ ΤΗΣ ΜΟΝΑΔΑΣ ΑΠΟ ΤΟ ΚΟΥΜΠΙ ΠΟΥ ΠΑΤΑΓΕ, ΧΩΡΙΣ ΝΑ ΚΑΝΩ ΤΙΠΟΤΑ.
// ΑΥΤΟ ΓΙΝΕΤΑΙ ΓΙΑΤΙ ΤΑ ΚΟΥΜΠΙΑ ΟΥΣΙΑΣΤΙΚΑ ΕΙΝΑΙ ΔΙΑΦΟΡΕΤΙΚΑ ΓΙΑ ΚΑΘΕ ΠΙΝΑΚΑ ΚΑΙ ΤΑΞΙΔΕΥΕΙ ΜΟΝΟ ΤΟ αα ΤΟΥ ΠΙΝΚΑ ΠΟΥ ΠΑΤΗΘΗΚΕ ΤΟ ΚΟΥΜΠΙ

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

    //$monada_pros = filter_input(INPUT_POST, 'monada_pros',FILTER_VALIDATE_INT);
    //$monada_pros = $_SESSION['id_monada'];
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

    if (isset($_POST['apodoxi_button'])) {
        //update action
        //ΘΑ ΚΑΝΩ ΕΝΑ ΕΡΩΤΗΜΑ ΝΑ ΔΩ ΑΝ ΤΟ BARCODE ΥΠΑΡΧΕΙ ΣΤΟΝ ΠΙΝΑΚΑ, ΔΗΛΑΔΗ, ΕΚΚΡΕΜΕΙ ΧΡΕΩΣΗ, ΑΛΛΙΩΣ ΘΑ ΤΟΝ ΑΝΑΚΑΤΕΥΘΥΝΩ ΣΕ ΣΕΛΙΔΑ ΣΦΑΛΜΑΤΟΣ
        //ΑΝ  ΥΠΑΡΧΕΙ, ΤΟΤΕ ΘΑ ΜΠΟΡΕΙ ΝΑ ΣΥΝΕΧΙΣΕΙ
        try{
            for($i=1; $i<=$sum; $i++){
                $qinsertekkxreoseis = "SELECT * from ekkremeis_xreoseis where barcode=?";
                $stmtinsertekkxreoseis = mysqli_prepare($dbcon, $qinsertekkxreoseis);
                mysqli_stmt_bind_param($stmtinsertekkxreoseis, 'i', $barcodeget[$i]);
                mysqli_stmt_execute($stmtinsertekkxreoseis);
                mysqli_stmt_store_result($stmtinsertekkxreoseis);
                if (mysqli_stmt_num_rows($stmtinsertekkxreoseis) == 0 ) {
                    mysqli_stmt_close($stmtinsertekkxreoseis);
                    mysqli_close($dbcon);
                    call_errorpage('Δεν πρέπει να φτάσουμε σε αυτό το σημείο! Κάποιο/α από τα υλικά ΔΕΝ είναι σε αναμονή έγκρισης!');
                    exit();
                }
                mysqli_stmt_close($stmtinsertekkxreoseis);
            }

            
            $monada_pros = $_SESSION['id_monada'];
            $user_apo = [];
            $parat = [];
            for($i=1; $i<=$sum; $i++){
                $qfindmonadabefore = "SELECT * from ekkremeis_xreoseis where barcode=?";
                $stmtfindmonadabefore = mysqli_prepare($dbcon, $qfindmonadabefore);
                mysqli_stmt_bind_param($stmtfindmonadabefore, 'i', $barcodeget[$i]);
                mysqli_stmt_execute($stmtfindmonadabefore);
                mysqli_stmt_store_result($stmtfindmonadabefore);
                mysqli_stmt_bind_result($stmtfindmonadabefore, $b1, $monada_apo, $monada_after, $user_apo[$i], $parat[$i]);
                mysqli_stmt_fetch($stmtfindmonadabefore);
                mysqli_stmt_close($stmtfindmonadabefore);
                if($monada_after==$monada_pros){
                    $qxreosi = "call xreosi_procedure(? , ? , ? , CURDATE() , ? , @error_bool)";
                    $stmtxreosi = mysqli_prepare($dbcon, $qxreosi);
                    mysqli_stmt_bind_param($stmtxreosi, 'iiis', $barcodeget[$i], $monada_pros, $user_apo[$i] , $parat[$i]);
                    mysqli_stmt_execute($stmtxreosi);
                    mysqli_stmt_store_result($stmtxreosi);
                    mysqli_stmt_close($stmtxreosi);
                } else {
                    mysqli_close($dbcon);
                    call_errorpage('Δεν πρέπει να φτάσουμε σε αυτό το σημείο! Πήγες να δεχτείς υλικά για άλλη μονάδα!');
                    exit(); 
                }
            }

            mysqli_close($dbcon);

            //ΣΕ ΑΥΤΟ ΤΟ ΣΗΜΕΙΟ ΘΑ ΚΑΝΕΙ DOWNLOAD ΤΟ ΧΡΕΩΣΤΙΚΟ


















            header("Location: prosparalavi.php");//ΘΑ ΤΟΝ ΠΑΩ ΣΤΙΣ ΑΝΑΜΟΝΕΣ
            exit();
        } catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }

        
    } else if (isset($_POST['aporripsi_button'])) {
        //delete action
        //ΘΑ ΚΑΝΩ ΕΝΑ ΕΡΩΤΗΜΑ ΝΑ ΔΩ ΑΝ ΤΟ BARCODE ΥΠΑΡΧΕΙ ΣΤΟΝ ΠΙΝΑΚΑ, ΔΗΛΑΔΗ, ΕΚΚΡΕΜΕΙ ΧΡΕΩΣΗ, ΑΛΛΙΩΣ ΘΑ ΤΟΝ ΑΝΑΚΑΤΕΥΘΥΝΩ ΣΕ ΣΕΛΙΔΑ ΣΦΑΛΜΑΤΟΣ
        //ΑΝ  ΥΠΑΡΧΕΙ, ΤΟΤΕ ΘΑ ΜΠΟΡΕΙ ΝΑ ΣΥΝΕΧΙΣΕΙ
        try{
            for($i=1; $i<=$sum; $i++){
                $qinsertekkxreoseis = "SELECT * from ekkremeis_xreoseis where barcode=?";
                $stmtinsertekkxreoseis = mysqli_prepare($dbcon, $qinsertekkxreoseis);
                mysqli_stmt_bind_param($stmtinsertekkxreoseis, 'i', $barcodeget[$i]);
                mysqli_stmt_execute($stmtinsertekkxreoseis);
                mysqli_stmt_store_result($stmtinsertekkxreoseis);
                if (mysqli_stmt_num_rows($stmtinsertekkxreoseis) == 0 ) {
                    mysqli_stmt_close($stmtinsertekkxreoseis);
                    mysqli_close($dbcon);
                    call_errorpage('Δεν πρέπει να φτάσουμε σε αυτό το σημείο! Κάποιο/α από τα υλικά ΔΕΝ είναι σε αναμονή έγκρισης!');
                    exit();
                }
                mysqli_stmt_close($stmtinsertekkxreoseis);
            }

            //ΤΟ ΔΕΥΤΕΡΟ ΒΗΜΑ ΘΑ ΕΙΝΑΙ Η ΔΙΑΓΡΑΦΗ ΤΩΝ ΤΙΜΩΝ ΣΤΟΝ ΠΙΝΑΚΑ ΧΩΡΙΣ ΦΟΒΟ ΓΙΑ ΕΓΓΡΑΦΕΣ ΠΟΥ ΔΕΝ ΥΠΑΡΧΟΥΝ
        
            for($i=1; $i<=$sum; $i++){
                $qdeletekkxreoseis = "DELETE FROM ekkremeis_xreoseis where barcode = ?";
                $stmtdeletekxreoseis = mysqli_prepare($dbcon, $qdeletekkxreoseis);
                mysqli_stmt_bind_param($stmtdeletekxreoseis, 'i', $barcodeget[$i]);
                mysqli_stmt_execute($stmtdeletekxreoseis);
                mysqli_stmt_close($stmtdeletekxreoseis);
            }

            mysqli_close($dbcon);
            header("Location: prosparalavi.php");//ΘΑ ΤΟΝ ΠΑΩ ΣΤΙΣ ΑΝΑΜΟΝΕΣ
            exit();
        } catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }

    } else {
        call_error_page('Δεν έπρεπε ποτέ να φτάσουμε σε αυτό το σημείο!');
    }



    


} else {
    call_error_page("Σφάλμα: Μη αποδεκτό αίτημα, λάθος κλήση της σελίδας!");
}//POST METHOD ONLY


mysqli_close($dbcon);
//ob_end_flush();
?>