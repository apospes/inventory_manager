<?php
$title = "Φόρμα Υλικού";
require('../templates/header.inc.php');
check_session();
check_no_admin_users();

$barcode = filter_input(INPUT_POST, 'barcode', FILTER_VALIDATE_INT);
$id_monada = filter_input(INPUT_POST, 'id_monada', FILTER_VALIDATE_INT);
$id_tmima = filter_input(INPUT_POST, 'id_tmima', FILTER_VALIDATE_INT);
$id_katigoria_prod = filter_input(INPUT_POST, 'id_katigoria_prod', FILTER_VALIDATE_INT);
$id_kataskeuastis = filter_input(INPUT_POST, 'id_kataskeuastis', FILTER_VALIDATE_INT);
$id_diktyo = filter_input(INPUT_POST, 'id_diktyo', FILTER_VALIDATE_INT);
$typos = filter_input(INPUT_POST, 'typos');
$merida = filter_input(INPUT_POST, 'merida');
$paratiriseis = filter_input(INPUT_POST, 'paratiriseis');
$leitourgiko = filter_input(INPUT_POST, 'leitourgiko', FILTER_VALIDATE_INT);
$xreomeno = filter_input(INPUT_POST, 'xreomeno', FILTER_VALIDATE_INT);
$sn = filter_input(INPUT_POST, 'sn');
$import_date = filter_input(INPUT_POST, 'import_date');

    //ΑΝ Η ΚΛΗΣΗ ΕΧΕΙ ΓΙΝΕΙ ΜΕ ΜΕΘΟΔΟ GET ΚΑΙ ΕΧΟΥΜΕ ΤΙΜΗ BARCODE, ΔΗΜΙΟΥΡΓΕΙΤΑΙ ΜΙΑ STICKY FORM------------------------------------------------------
if  ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['barcode'])) {
    $barcode = filter_input(INPUT_GET, 'barcode', FILTER_VALIDATE_INT);

    $qgetbybarcode = "SELECT id_tmima, id_katigoria_prod, id_kataskeuastis, id_diktyo, typos, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date, diegrameno, delete_date FROM products WHERE barcode= ? ";
    $stmtgetbybarcode = mysqli_prepare($dbcon, $qgetbybarcode);
    mysqli_stmt_bind_param($stmtgetbybarcode, 'i', $barcode);
    mysqli_stmt_execute($stmtgetbybarcode);
    mysqli_stmt_store_result($stmtgetbybarcode);
    mysqli_stmt_bind_result($stmtgetbybarcode, $id_tmima, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date, $diegrameno, $delete_date);
    mysqli_stmt_fetch($stmtgetbybarcode);
    if (mysqli_stmt_num_rows($stmtgetbybarcode) == 1) {
        
        //$id_tmima = intval($id_tmima);
        $id_monada = get_monada_by_tmima($id_tmima, $dbcon);
        $id_monada = intval($id_monada);

        
        if ($diegrameno == 1) {
            call_errorpage("Το υλικό με barcode: $barcode, έχει διαγραφεί στις $delete_date.");
        } else {
            if (($_SESSION['id_rolos'] == 1) || ($id_monada == $_SESSION['id_monada']) ) {
                null; // ΕΧΕΙ ΠΑΡΑΣΕΙ ΑΠΟ ΟΛΟΥΣ ΤΟΥΣ ΕΛΕΓΧΟΥΣ ΓΙΑ ΤΗΝ ΜΕΘΟΔΟ GET
            } else {
                call_errorpage('Μη δικαίωμα διαχείρισης του υλικού!');
            }
        }
    } else {
        call_errorpage('Μη αποδεκτή τιμή barcode!');
    }

//AN H ΜΕΘΟΔΟΣ ΕΙΝΑΙ GET ΧΩΡΙΣ ΤΙΜΗ BARCODE, 
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['barcode'])) {

    null;//ΘΕΛΟΥΜΕ ΝΑ ΠΑΕΙ ΣΤΗΝ ΦΟΡΜΑ

    //ΑΝ Η ΜΕΘΟΔΟΣ ΕΙΝΑΙ POST, ΠΡΕΠΕΙ ΝΑ ΓΙΝΟΥΝ ΟΛΟΙ ΟΙ ΕΛΕΓΧΟΙ----------------------------------------------------------
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $typos = strip_tags($typos);
    $merida = strip_tags($merida);
    $paratiriseis = strip_tags($paratiriseis);
    $sn = strip_tags($sn);
    $errors = [];

    //ΕΙΣΑΓΩΓΗ ΛΑΘΩΝ ΚΕΝΩΝ ΠΕΔΙΩΝ
    //if (empty($barcode)) { $errors[1] = 'Δεν εχετε δώσει Barcode!' ;}
    if (empty($id_monada)) { $errors[2] = 'Δεν εχετε δώσει Μονάδα!' ;}
    //if (empty($id_tmima)) { $errors[3] = 'Δεν εχετε δώσει Τμήμα!' ;}
    if (empty($id_katigoria_prod)) { $errors[4] = 'Δεν εχετε δώσει Κατηγορία!' ;}
    if (empty($id_kataskeuastis)) { $errors[5] = 'Δεν εχετε δώσει Κατασκευαστή!' ;}
    if (empty($id_diktyo)) { $errors[6] = 'Δεν εχετε δώσει δίκτυο!' ;}
    if (empty($typos)) { $errors[7] = 'Δεν εχετε δώσει τύπο!' ;}
    if (empty($merida)) { $errors[8] = 'Δεν εχετε δώσει μερίδα!' ;}
    if (empty($paratiriseis)) { $errors[9] = 'Δεν εχετε δώσει παρατηρήσεις!' ;}
    //if (empty($leitourgiko)) { $errors[10] = 'Δεν εχετε δώσει λειτουργικότητα!' ;}
    //if (empty($xreomeno)) { $errors[11] = 'Δεν εχετε δώσει χρέωση!' ;}
    if (empty($sn)) { $errors[12] = 'Δεν εχετε δώσει Serial Number!' ;}
    if (empty($import_date)) { $errors[13] = 'Δεν εχετε δώσει Ημερομηνία Καταχώρησης!' ;}


    if (!empty($errors)) {
        null; //ΠΡΕΠΕΙ ΠΡΩΤΑ ΝΑ ΔΙΟΡΘΩΘΟΥΝ ΤΑ ΛΑΘΗ
    } else {
        //ΕΔΩ ΘΑ ΓΙΝΟΥΝ ΠΡΑΓΜΑΤΑ ΜΕ ΤΗΝ ΒΑΣΗ
        try {
            if (!empty($barcode)) {
                $qfindbarcode = "SELECT count(*) from products where barcode=?" ;
                $stmtfindbarcode = mysqli_prepare($dbcon, $qfindbarcode);
                mysqli_stmt_bind_param($stmtfindbarcode, 'i', $barcode);
                mysqli_stmt_execute($stmtfindbarcode);
                mysqli_stmt_store_result($stmtfindbarcode);
                mysqli_stmt_bind_result($stmtfindbarcode, $existisbarcode);
                mysqli_stmt_fetch($stmtfindbarcode);
                if ($existisbarcode==1) {
                    //ΥΠΑΡΧΕΙ ΑΡΑ ΘΑ ΚΑΝΟΥΜΕ UPDATE
                    try {
                        if ($_SESSION['id_rolos']==2) {
                            $qupdateproduct = "UPDATE products SET id_tmima=?, id_katigoria_prod=?, id_kataskeuastis=? , id_diktyo=?, typos=?, merida=?, paratiriseis=?, leitourgiko=?, xreomeno=?, sn=?, import_date=? WHERE barcode = ?";
                            $stmtupdateproduct = mysqli_prepare($dbcon, $qupdateproduct);
                            mysqli_stmt_bind_param($stmtupdateproduct,'iiiisssiissi',  $id_tmima, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date, $barcode);
                        } else {
                            $qupdateproduct = "UPDATE products SET id_tmima=get_default_tmima_by_monada(?), id_katigoria_prod=?, id_kataskeuastis=? , id_diktyo=?, typos=?, merida=?, paratiriseis=?, leitourgiko=?, xreomeno=?, sn=?, import_date=? WHERE barcode = ?";
                            $stmtupdateproduct = mysqli_prepare($dbcon, $qupdateproduct);
                            mysqli_stmt_bind_param($stmtupdateproduct,'iiiisssiissi',  $id_monada, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date, $barcode);
                        }
                        
                        mysqli_stmt_execute($stmtupdateproduct);
                        if (mysqli_stmt_affected_rows($stmtupdateproduct) == 1 ) {
                            mysqli_stmt_close($stmtupdateproduct);
                            
                            header("Location: index.php");
                            exit();
                        } else {
                            call_errorpage('Σφάλμα στα δεδομένα μετατροπής!');
                        }


                        mysqli_stmt_close($stmtupdateproduct);
                    
                    } catch (mysqli_sql_exception $e) {
                        var_dump($e);
                        $errorpage_url = "errorpage.php?e=" . urlencode($e);
                        header("Location: $errorpage_url");
                        exit();
                    }

                } else {
                    call_errorpage('ΠΩΣ ΓΙΝΕΤΑΙ ΝΑ ΕΠΕΣΤΡΕΨΑΝ ΠΑΡΑΠΑΝΩ ΑΠΟ 1 Ή ΑΡΝΗΤΙΚΑ BARCODE, ΟΥΤΕ ΕΔΩ ΠΡΕΠΕΙ ΝΑ ΦΤΑΣΟΥΜΕ!!!');
                }
                mysqli_stmt_close($stmtfindbarcode);

            } else {

                 //ΔΕΝ ΥΠΑΡΧΕΙ ΑΡΑ ΘΑ ΚΑΝΟΥΜΕ ΕΙΣΑΓΩΓΗ
                 try {
                    if ($_SESSION['id_rolos']==1) {
                        $qinsertproduct = "INSERT INTO products (id_tmima, id_katigoria_prod, id_kataskeuastis, id_diktyo, typos, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date) 
                            VALUES (get_default_tmima_by_monada(?),?,?,?,?,?,?,?,?,?,?);";
                        $stmtinsertproduct = mysqli_prepare($dbcon, $qinsertproduct);
                    
                        mysqli_stmt_bind_param($stmtinsertproduct,'iiiisssiiss', $id_monada, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date);
                    } else {
                        $qinsertproduct = "INSERT INTO products ( id_tmima, id_katigoria_prod, id_kataskeuastis, id_diktyo, typos, merida, paratiriseis, leitourgiko, xreomeno, sn, import_date) 
                            VALUES (?,?,?,?,?,?,?,?,?,?,?);";
                        $stmtinsertproduct = mysqli_prepare($dbcon, $qinsertproduct);
                        mysqli_stmt_bind_param($stmtinsertproduct,'iiiisssiiss',  $id_tmima, $id_katigoria_prod, $id_kataskeuastis, $id_diktyo, $typos, $merida, $paratiriseis, $leitourgiko, $xreomeno, $sn, $import_date);
                    }
                    mysqli_stmt_execute($stmtinsertproduct);
                    if (mysqli_stmt_affected_rows($stmtinsertproduct) == 1 ) {
                        mysqli_stmt_close($stmtinsertproduct);
                        
                        header("Location: index.php");
                        exit();
                    } else {
                        call_errorpage('Σφάλμα στα δεδομένα εισαγωγής!');
                    }

                    mysqli_stmt_close($stmtinsertproduct);

                } catch (mysqli_sql_exception $e) {
                    var_dump($e);
                    $errorpage_url = "errorpage.php?e=" . urlencode($e);
                    header("Location: $errorpage_url");
                    exit();
                }
            
            
            }

        }catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }
    }



//ΔΕΝ ΘΑ ΚΛΕΙΘΕΙ ΠΟΤΕ----------------------------------------------------------------------------------------------
} else { 
    call_errorpage('ΑΠΟΣΔΙΟΡΙΣΤΟ ΛΑΘΟΣ, ΔΕΝ ΕΠΡΕΠΕ ΝΑ ΦΤΑΣΟΥΜΕ ΠΟΤΕ ΣΕ ΑΥΤΟ ΤΟ ΣΗΜΕΙΟ');
}

//ΦΟΡΜΑ------------------------------------------------------------------------------------------------------------
?>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-sm-12 col-xl-8">
            <div class="bg-light rounded h-100 p-4">
            <h3 class="mb-4">Φόρμα Υλικού</h3>
            <?php if (!empty($errors)) { print_error_message("Παρακαλώ διορθώστε τα παρακάτω σφάλματα:"); } ?>
            <form method="post" action="">
                <div class="row mb-3">
                    <label for="barcode" <?php if (empty($barcode)) { echo 'hidden';}?> class="col-sm-2 col-form-label">Barcode</label>
                    <div class="col-sm-10">
                        <input type="text" name="barcode" <?php if (!empty($barcode)) {print "value='$barcode' required readonly";
                                                                } else {
                                                                    
                                                                    print "value='' hidden readonly" ;} ?> class="form-control" id="barcode" >
                    </div>
                </div>
                <?//php if (!empty($errors[1])) { print_error_message($errors[1]); } ?>                                                        

                <div class="row mb-3">
                    <label for="id_monada" class="col-sm-2 col-form-label">Μονάδα</label>
                    <div class="col-sm-10">
                    <select class="form-select" name="id_monada" id="id_monada" aria-label="Default select example" <?php if ($_SESSION['id_rolos']==2) { echo 'readonly'; } ?> required>
                        <?php if ($_SESSION['id_rolos']==1 && empty($id_monada)) { ?>
                        <option value="">Μονάδα (*)</option>
                        <?php dropdown_menus_by_table('monada', $dbcon, $id_monada); 
                        } elseif ($_SESSION['id_rolos']==1 && !empty($id_monada)) { ?>
                        <?php dropdown_menus_by_table('monada', $dbcon, $id_monada);
                         } else {?>
                        <option value="<?php echo $_SESSION['id_monada']?>" selected><?php echo print_by_id('monada', $_SESSION['id_monada'], $dbcon)?></option>
                        <?php } ?>
                    </select>
                    </div>
                </div>
                <?php if (!empty($errors[2])) { print_error_message($errors[2]); } ?>
                
                
                <div class="row mb-3">
                    <label for="id_tmima" class="col-sm-2 col-form-label">Τμήμα</label>
                    <div class="col-sm-10">
                    <select class="form-select mb-3" id="id_tmima" name="id_tmima" aria-label="Default select example" <?php if ($_SESSION['id_rolos'] == 1) { echo 'readonly' ;} else { echo 'required' ; } ?> >
                        <?php if ($_SESSION['id_rolos']==2) { ?>
                        <option value="">Τμήμα (*)</option>
                        <?php dropdown_menus_by_table('tmima', $dbcon, $id_tmima, $_SESSION['id_monada'])  ?>
                        <?php } else {
                            print "<option selected>Γενική Διαχείριση</option>";
                        } ?>
                    </select>
                    </div>
                </div>
                <?//php if (!empty($errors[3])) { print_error_message($errors[3]); } ?>       
                
                
                <div class="row mb-3">
                    <label for="id_katigoria_prod" class="col-sm-2 col-form-label">Κατηγορία</label>
                    <div class="col-sm-10">
                    <select class="form-select mb-3" name="id_katigoria_prod" aria-label="Default select example" required>
                        <option value="">Κατηγορία (*)</option>
                        <?php dropdown_menus_by_table('katigoria_prod', $dbcon, $id_katigoria_prod); ?>
                    </select>
                    </div>
                </div>
                <?php if (!empty($errors[4])) { print_error_message($errors[4]); } ?>        
                <div class="row mb-3">
                    <label for="id_kataskeuastis" class="col-sm-2 col-form-label">Κατασκευαστής</label>
                    <div class="col-sm-10">
                    <select class="form-select mb-3" name="id_kataskeuastis" aria-label="Default select example" required>
                        <option value="">Κατασκευαστής (*)</option>
                        <?php dropdown_menus_by_table('kataskeuastis', $dbcon, $id_kataskeuastis); ?>
                    </select>
                    </div>
                </div>
                <?php if (!empty($errors[5])) { print_error_message($errors[5]); } ?>        
                <div class="row mb-3">
                    <label for="id_diktyo" class="col-sm-2 col-form-label">Δίκτυο</label>
                    <div class="col-sm-10">
                    <select class="form-select mb-3" name="id_diktyo" aria-label="Default select example" required>
                        <option value="">Δίκτυο (*)</option>
                        <?php dropdown_menus_by_table('diktyo', $dbcon, $id_diktyo); ?>
                    </select>
                    </div>
                </div>
                <?php if (!empty($errors[6])) { print_error_message($errors[6]); } ?>        
                <div class="row mb-3">
                    <label for="typos" class="col-sm-2 col-form-label">Τύπος</label>
                    <div class="col-sm-10">
                        <input type="text" name="typos" value="<?php echo $typos?>" class="form-control" id="typos" required>
                    </div>
                </div>
                <?php if (!empty($errors[7])) { print_error_message($errors[7]); } ?>        

                <div class="row mb-3">
                    <label for="merida" class="col-sm-2 col-form-label">Μερίδα</label>
                    <div class="col-sm-10">
                        <input type="text" name="merida" value="<?php echo $merida?>" class="form-control" id="merida" required>
                    </div>
                </div>
                <?php if (!empty($errors[8])) { print_error_message($errors[8]); } ?>        
                <div class="row mb-3">
                    <label for="paratiriseis" class="col-sm-2 col-form-label">Παρατηρήσεις</label>
                    <div class="col-sm-10">
                        <textarea name="paratiriseis" class="form-control" id="paratiriseis" required><?php echo $paratiriseis?></textarea>
                    </div>
                </div>
                <?php if (!empty($errors[9])) { print_error_message($errors[9]); } ?>  
                
                

                <div class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Λειτουργικό</legend>
                    <div class="col-sm-10">

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" required type="radio" name="leitourgiko" id="inlineRadio01" value="1" 
                            <?php if ($leitourgiko==null || $leitourgiko==1) { 
                                echo 'checked';}?> >
                            <label class="form-check-label" for="inlineRadio01">Ναι</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="leitourgiko" id="inlineRadio02"value="0" 
                            <?php if ($leitourgiko===0) { 
                                echo 'checked';} ?>>
                            <label class="form-check-label" for="inlineRadio02">Όχι</label>
                        </div>  
                    </div>
                </div>  
                <?//php if (!empty($errors[10])) { print_error_message($errors[10]); } ?>



                <div class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Χρεωμένο</legend>
                    <div class="col-sm-10">

                    <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="xreomeno" id="inlineRadio1" value="1" 
                            <?php if ($xreomeno==null || $xreomeno==1) { 
                                echo 'checked';}?> >
                            <label class="form-check-label" for="inlineRadio1">Ναι</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"  type="radio" name="xreomeno" id="inlineRadio2" value="0" 
                            <?php if ($xreomeno===0) { 
                                echo 'checked';} ?>>
                            <label class="form-check-label" for="inlineRadio2">Όχι</label>
                        </div>   
                    </div>
                </div>  
                <?//php if (!empty($errors[11])) { print_error_message($errors[11]); } ?>
                
                
                
                <div class="row mb-3">
                    <label for="sn" class="col-sm-2 col-form-label">Serial Number</label>
                    <div class="col-sm-10">
                        <input type="text" name="sn" value="<?php echo $sn?>" class="form-control" id="sn" required>
                    </div>
                </div>
                <?php if (!empty($errors[12])) { print_error_message($errors[12]); } ?>                                    


                <div class="row mb-3">
                    <label for="import_date" class="col-sm-2 col-form-label">Ημερομηνία Εισαγωγής</label>
                    <div class="col-sm-10">
                        <input type="date" name="import_date" value="<?php echo $import_date ?>" class="form-control" id="import_date" required >
                    </div>
                </div>
                <?php if (!empty($errors[13])) { print_error_message($errors[13]); } ?>


                <button type="submit" class="btn btn-primary">Υποβολή</button>
            </form>
        </div>
        </div>
    </div>
</div>






<?php
require('../templates/footer.inc.php');
?>