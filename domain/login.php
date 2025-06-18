<?php
$title = "Σύνδεση";
require('../templates/header.inc.php');
check_no_session();
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = strip_tags($username);
    $errors = [];
    if (empty($username)) {
        $errors[1] = 'Δεν έχετε δώσει όνομα χρήστη!';
    }
    if (empty($password)) {
        $errors[2] = 'Δεν έχετε δώσει password!';
    }
    if (!empty($errors)) {
        null;
    } else {

        try{
            $qlogin = "SELECT id_users, firstname, lastname, user_password, id_oplo, id_vathmos, id_rolos, id_monada, energos FROM users WHERE username=?";
            $stmtlogin = mysqli_prepare($dbcon, $qlogin);
            mysqli_stmt_bind_param($stmtlogin, 's', $username);
            mysqli_stmt_execute($stmtlogin);
            mysqli_stmt_store_result($stmtlogin);
            if (mysqli_stmt_num_rows($stmtlogin) == 1) {
                mysqli_stmt_bind_result($stmtlogin, $id_users, $firstname, $lastname, $password_hashed, $id_oplo, $id_vathmos, $id_rolos, $id_monada, $energos);
                mysqli_stmt_fetch($stmtlogin);           
                if ($energos == true) {
                    if (password_verify($password, $password_hashed)) {
                        // Έχουμε βρει επιτυχώς ζεύγος username/password στη ΒΔ
                        $_SESSION['id_user'] = $id_users;
                        $_SESSION['username'] = $username;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['id_oplo'] = $id_oplo;
                        $_SESSION['id_vathmos'] = $id_vathmos;
                        $_SESSION['id_rolos'] = $id_rolos;
                        $_SESSION['id_monada'] = $id_monada;
                        $_SESSION['agent'] = sha1($_SERVER['HTTP_USER_AGENT']);
                        $_SESSION['time'] = time();
    
                        mysqli_stmt_close($stmtlogin);
                        mysqli_close($dbcon);
                        ob_end_clean();  // Διαγράφουμε ό,τι έχει παραχθεί
                        header("Location: index.php\n"); // Ανακατεύθυνση στη αρχική σελίδα
                        exit();
                    } else {
                        //ΣΩΣΤΟ USERNAME ΑΛΛΑ ΛΑΘΟΣ PASSWORD.
                        $errors[3] = 'Ο συνδυασμός username/password δεν είναι αποδεκτός!';
                    }
                } elseif ($energos == false) { //ΑΝ ΔΕΝ ΕΧΕΙ ΕΓΚΡΙΘΕΙ ΑΠΟ ΤΟΝ ΔΙΑΧΕΙΡΙΣΤΗ
                    mysqli_stmt_close($stmtlogin);
                    mysqli_close($dbcon);
                    header("Location: helpdesk.php?msg='login'");
                    exit();
        
                } else { 
                    mysqli_stmt_close($stmtlogin);
                    mysqli_close($dbcon);
                    header("Location: errorpage.php");
                    exit();
                }
            } else {
                //ΛΑΘΟΣ USERNAME.
                $errors[3] = 'Ο συνδυασμός username/password δεν είναι αποδεκτός!';
            }
            mysqli_stmt_close($stmtlogin);
        }catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }// try-catch



    }
}
?>
<!--ΦΟΡΜΑ ΣΥΝΔΕΣΗΣ-->
<div class="container-fluid pt-4 px-4">
    <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="index.php" class="">
                        <h3 class="text-primary"><i class="fa fa-folder-open me-2"></i>ΔΙΑΧΕΙΡΙΣΗ</h3>
                    </a>
                        <h3>Σύνδεση</h3>
                </div>
                <?php if (!empty($errors)) { print_error_message("Παρακαλώ διορθώστε τα παρακάτω σφάλματα:"); } ?>
                <form method="post" action="">
                
                <div class="form-floating mb-3">
                        <input type="text" value="<?php echo $username?>" name="username" class="form-control" id="floatingInput" placeholder="Username" required>
                        <label for="floatingInput">Όνομα χρήστη (*)</label>
                </div>
                <?php if (!empty($errors[1])) { print_error_message($errors[1]); } ?>
                
                <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Κωδικός (*)</label>
                </div>
                <?php if (!empty($errors[2])) { print_error_message($errors[2]); } ?>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <a href="helpdesk.php?msg='pass'">Ξεχάσατε τον κωδικό;</a>
                </div>
                <?php if (!empty($errors[3])) { print_error_message($errors[3]); } ?>
                <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Σύνδεση</button>
                </form>
                <p class="text-center mb-0">Δεν έχετε λογαριασμό; <a href="register.php">Εγγραφή</a></p>
            </div>
        </div>
    </div>
</div>

<?php
    
require('../templates/footer.inc.php');
?>