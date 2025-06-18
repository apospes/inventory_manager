<?php
$title = "Εγγραφή";
require('../templates/header.inc.php');
check_no_session();

$firstname = filter_input(INPUT_POST, 'firstname');
$lastname = filter_input(INPUT_POST, 'lastname');
$id_vathmos = filter_input(INPUT_POST, 'id_vathmos', FILTER_VALIDATE_INT);
$id_oplo = filter_input(INPUT_POST, 'id_oplo', FILTER_VALIDATE_INT);
$id_rolos = filter_input(INPUT_POST, 'id_rolos', FILTER_VALIDATE_INT);
$id_monada = filter_input(INPUT_POST, 'id_monada', FILTER_VALIDATE_INT);
$username = filter_input(INPUT_POST, 'username');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$password2 = filter_input(INPUT_POST, 'password2');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = strip_tags($firstname);
    $lastname = strip_tags($lastname);
    $username = strip_tags($username);
    $email = strip_tags($email);
    $errors = [];
    // ΕΙΣΑΓΩΓΗ ΛΑΘΩΝ ΚΕΝΩΝ ΠΕΔΙΩΝ
    if (empty($lastname)) {
        $errors[1] = 'Δεν έχετε δώσει Επίθετο!';
    }
    if (empty($firstname)) {
        $errors[2] = 'Δεν έχετε δώσει Όνομα!';
    }
    if (empty($id_vathmos)) {
        $errors[3] = 'Δεν έχετε δώσει Βαθμό!';
    }
    if (empty($id_oplo)) {
        $errors[4] = 'Δεν έχετε δώσει Ειδικότητα!';
    }
    if (empty($id_monada)) {
        $errors[5] = 'Δεν έχετε δώσει Μονάδα!';
    }
    if (empty($username)) {
        $errors[6] = 'Δεν έχετε δώσει Όνομα Χρήστη!';
    }
    if (empty($password)) {
        $errors[7] = 'Δεν έχετε δώσει Κωδικό!';
    }
    if (empty($password2)) {
        $errors[8] = 'Δεν έχετε δώσει Επαλήθευση Κωδικού!';
    }
    if ($password != $password2) {
        $errors[9] = 'O κωδικός και η επαλήθευση κωδικού δεν ταιριάζουν';
    }

    try {
        $qcheckregister = "SELECT username from users where username = ?";
        $stmtcheckregister = mysqli_prepare($dbcon, $qcheckregister);
        mysqli_stmt_bind_param($stmtcheckregister, 's', $username);
        mysqli_stmt_execute($stmtcheckregister);
        mysqli_stmt_store_result($stmtcheckregister);
        if (mysqli_stmt_num_rows($stmtcheckregister) !=0 ) {
            $errors[10] = 'Ο χρήστης υπάρχει ήδη, το όνομα χρήστη χρησιμοποιείται!';
        }
        mysqli_stmt_close($stmtcheckregister);
    } catch (mysqli_sql_exception $e) {
        var_dump($e);
        $errorpage_url = "errorpage.php?e=" . urlencode($e);
        header("Location: $errorpage_url");
        exit();
    }


    if (!empty($errors)) { //ΕΑΝ ΠΑΡΟΥΣΙΑΣΤΙΚΑΝ ΚΑΠΟΙΑ ΑΠΟ ΤΑ 10 ΛΑΘΗ ΠΑΡΑΠΑΝΩ
        null;
    } else { //ΕΑΝ ΔΕΝ ΥΠΑΡΧΟΥΝ ΣΦΑΛΜΑΤΑ ΣΤΗΝ ΣΥΜΠΛΗΡΩΣΗ ΤΗΣ ΦΟΡΜΑΣ, ΘΑ ΤΑ ΕΙΣΑΓΟΥΜΕ ΣΤΗΝ ΒΑΣΗ

        try{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $qregister = "INSERT INTO users (username, firstname, lastname, user_password, email, id_oplo, id_vathmos, id_monada) VALUES (?,?,?,?,?,?,?,?)";
            $stmtregister = mysqli_prepare($dbcon, $qregister);
            mysqli_stmt_bind_param($stmtregister, 'sssssiii', $username, $firstname, $lastname, $hash, $email, $id_oplo, $id_vathmos, $id_monada);
            mysqli_stmt_execute($stmtregister);
            if (mysqli_stmt_affected_rows($stmtregister) == 1 ) {
                mysqli_stmt_close($stmtregister);
                mysqli_close($dbcon);
                header("Location: helpdesk.php?msg='success_register'");
                exit();
            } else {
                call_errorpage('Σφάλμα στα δεδομένα εισαγωγής!');
            }

        }catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }

    }

}//ΕΑΝ ΕΧΕΙ ΓΙΝΕΙ POST

?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="index.php" class="">
                        <h3 class="text-primary"><i class="fa fa-folder-open me-2"></i>ΔΙΑΧΕΙΡΙΣΗ</h3>
                    </a>
                    <h3>Εγγραφή</h3>
                </div>
                <?php if (!empty($errors)) { print_error_message("Παρακαλώ διορθώστε τα παρακάτω σφάλματα:"); } ?>
                <form method="post" action="">
                <!--ΦΟΡΜΑ ΕΓΓΡΑΦΗΣ-->
                <?php if (!empty($errors[10])) { print_error_message($errors[10]); } ?>
                <div class="form-floating mb-3">
                    <input type="text" name="lastname" value="<?php echo $lastname?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                    <label for="floatingText">Επίθετο (*)</label>
                </div>
                <?php if (!empty($errors[1])) { print_error_message($errors[1]); } ?>
                <div class="form-floating mb-3">
                    <input type="text" name="firstname" value="<?php echo $firstname?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                    <label for="floatingText">Όνομα (*)</label>
                </div>
                <?php if (!empty($errors[2])) { print_error_message($errors[2]); } ?>
                <select class="form-select mb-3" name="id_vathmos" aria-label="Default select example" required>
                    <option>Βαθμός (*)</option>
                    <?php dropdown_menus_by_table('vathmos', $dbcon, $id_vathmos); ?>
                </select>
                <?php if (!empty($errors[3])) { print_error_message($errors[3]); } ?>
                <select class="form-select mb-3" name="id_oplo" aria-label="Default select example" required>
                    <option>Ειδικότητα (*)</option>
                    <?php dropdown_menus_by_table('oplo', $dbcon, $id_oplo); ?>
                </select>
                <?php if (!empty($errors[4])) { print_error_message($errors[4]); } ?>
                <select class="form-select mb-3" name="id_monada" aria-label="Default select example" required>
                    <option>Μονάδα (*)</option>
                    <?php dropdown_menus_by_table('monada', $dbcon, $id_monada); ?>
                </select>
                <?php if (!empty($errors[5])) { print_error_message($errors[5]); } ?>
                <div class="form-floating mb-3">
                    <input type="text" name="username" value="<?php echo $username?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                    <label for="floatingText">Όνομα χρήστη (*)</label>
                </div><?php if (!empty($errors[6])) { print_error_message($errors[6]); } ?>
                <div class="form-floating mb-3">
                    <input type="email" name="email" value="<?php echo $email?>" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                    <label for="floatingPassword">Κωδικός (*)</label>
                </div>
                <?php if (!empty($errors[7])) { print_error_message($errors[7]); } ?>
                <div class="form-floating mb-3">
                    <input type="password" name="password2" class="form-control" id="floatingPassword" placeholder="Password" required>
                    <label for="floatingPassword">Επαλήθευση κωδικού (*)</label>
                </div>
                <?php if (!empty($errors[8])) { print_error_message($errors[8]); } ?>
                <?php if (!empty($errors[9])) { print_error_message($errors[9]); } ?>
                

                <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Εγγραφή</button>
                </form>
                <p class="text-center mb-0">Έχετε ήδη λογαριασμό; <a href="login.php">Σύνδεση</a></p>
                <!-- ΤΕΛΟΣ ΦΟΡΜΑΣ ---------------------------------------------------------------------------------- -->
            </div>
        </div>
    </div>
</div>

<?php
require('../templates/footer.inc.php');
?>