<?php
$title = "Αλλαγή Κωδικού";
require('../templates/header.inc.php');
check_session();

ob_start(); // Αρχίζουμε το output buffering


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id_user'];
    $oldpass = strip_tags(filter_input(INPUT_POST, 'oldpass'));
    $newpass = strip_tags(filter_input(INPUT_POST, 'newpass'));
    $newpass2 = strip_tags(filter_input(INPUT_POST, 'newpass2'));
    $errors = [];
    if (empty($oldpass)) {
        $errors[1] = 'Δεν έχετε δώσει παλίο κωδικό!';
    }
    if (empty($newpass)) {
        $errors[2] = 'Δεν έχετε νέο κωδικό!';
    }
    if (empty($newpass2)) {
        $errors[3] = 'Δεν έχετε δώσει επιβεβαίωση νέου κωδικού!';
    }
    if ($newpass != $newpass2) {
        $errors[4] = 'O κωδικός και η επαλήθευση κωδικού δεν ταιριάζουν!';
    }
    if (!empty($errors)) {
        null;
    } else {
        



        $qcheck = "SELECT user_password FROM users WHERE id_users=?;";
        $stmtcheck = mysqli_prepare($dbcon, $qcheck);
        mysqli_stmt_bind_param($stmtcheck, 'i', $id_user);
        mysqli_stmt_execute($stmtcheck);
        mysqli_stmt_store_result($stmtcheck);
        if (mysqli_stmt_num_rows($stmtcheck) == 1) {
            mysqli_stmt_bind_result($stmtcheck, $currpass);
            mysqli_stmt_fetch($stmtcheck);           
                if (password_verify($oldpass, $currpass)) {
                    // Έχουμε βρει επιτυχώς το παρόν password στη ΒΔ
                    mysqli_stmt_close($stmtcheck);

                    $newhash = password_hash($newpass, PASSWORD_DEFAULT);

                    $qchange = "UPDATE users SET user_password=? where id_users=?;";
                    $stmtchange = mysqli_prepare($dbcon, $qchange);
                    mysqli_stmt_bind_param($stmtchange, 'si', $newhash, $id_user);
                    mysqli_stmt_execute($stmtchange);
                    mysqli_stmt_close($stmtchange);
                    //ob_end_flush();
                    header("Location: index.php" );
                    exit();


                } else {
                    //ΛΑΘΟΣ PASSWORD.
                    mysqli_stmt_close($stmtcheck);
                    $errors[3] = 'Ο παλιός κωδικός δεν είναι έγκυρος!';
                }
        } else {
            //ΔΕΝ ΕΓΙΝΑΝ FΕTCH
            mysqli_stmt_close($stmtcheck);
            call_errorpage('Δεν έπρεπε να φτάσουμε σε αυτό το σημείο. Δεν βρέθηκε ο χρήστης στην βάση!');
        }
    }
}
?>
<!--ΦΟΡΜΑ ΑΛΛΑΓΗΣ ΚΩΔΙΚΟΥ-->
<div class="container-fluid pt-4 px-4">
    <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-center mb-3">
                        <h3>Αλλαγή Κωδικού</h3>
                </div>
                <?php if (!empty($errors)) { print_error_message("Παρακαλώ διορθώστε τα παρακάτω σφάλματα:"); } ?>
                <form method="post" action="">
                
                <div class="form-floating mb-3">
                        <input type="password" name="oldpass" class="form-control" id="floatingInput" placeholder="OldPassword" required>
                        <label for="floatingInput">Παλιός Κωδικός</label>
                </div>
                <?php if (!empty($errors[1])) { print_error_message($errors[1]); } ?>
                
                <div class="form-floating mb-3">
                    <input type="password" name="newpass" class="form-control" id="floatingPassword" placeholder="NewPassword" required>
                    <label for="floatingPassword">Νέος Κωδικός</label>
                </div>
                <?php if (!empty($errors[2])) { print_error_message($errors[2]); } ?>
                <div class="form-floating mb-3">
                    <input type="password" name="newpass2" class="form-control" id="floatingPassword" placeholder="NewPassword2" required>
                    <label for="floatingPassword">Επαλήθευση κωδικού</label>
                </div>
                <?php if (!empty($errors[3])) { print_error_message($errors[3]); } ?>

                <?php if (!empty($errors[4])) { print_error_message($errors[4]); } ?>
                <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Επιβεβαίωση</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php
    
require('../templates/footer.inc.php');
?>