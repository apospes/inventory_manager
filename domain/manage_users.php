<?php
$title = "Διαχείριση Χρηστών";
require('../templates/header.inc.php');
check_session();
check_admin_users();
?>
            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 auto-width">
                            <h3 class="mb-4">ΕΝΕΡΓΟΙ ΧΡΗΣΤΕΣ</h3>

                            <!-- Search Bar Start -->
                            <div id="search">
                                <form method="post" action="" class="form-inline">
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control mb-3" name="id_users" placeholder="User id">
                                        <input type="text" class="form-control mb-3" name="email" placeholder="E-mail">

                                        <select class="form-select mb-3" name="id_vathmos">
                                            <option value="">Βαθμός</option>
                                            <?php dropdown_menus_by_table('vathmos', $dbcon); ?>
                                        </select>

                                        <select class="form-select mb-3" name="id_oplo">
                                            <option value="">Όπλο</option>
                                            <?php dropdown_menus_by_table('oplo', $dbcon); ?>
                                        </select>

                                        <input type="text" class="form-control mb-3" name="lastname" placeholder="Επώνυμο">

                                        <select class="form-select mb-3" name="id_monada">
                                            <option value="">Μονάδα</option>
                                            <?php dropdown_menus_by_table('monada', $dbcon); ?>
                                        </select>
                                    </div>
                                    
                                    <p><button type="submit" class="btn btn-primary">Αναζήτηση με χρήση των παραπάνω φίλτρων</button></p>
                                </form>
                            </div>
                            <!-- Search Bar End -->

                            <table class="table table-striped text-center custom-table align-middle" style="display: block; height: 350px; overflow-y: scroll">
                                <thead style="position: sticky; top:0">
                                    <tr class="bg-light">
                                        <th scope="col">USER ID</th>
                                        <th scope="col">EMAIL</th>
                                        <th scope="col">ΒΑΘΜΟΣ</th>
                                        <th scope="col">ΟΠΛΟ</th>
                                        <th scope="col">ΟΝΟΜΑ</th>
                                        <th scope="col">ΕΠΩΝΥΜΟ</th>
                                        <th scope="col">ΡΟΛΟΣ</th>
                                        <th scope="col">ΜΟΝΑΔΑ</th>
                                        <th scope="col" colspan="2">ΕΝΕΡΓΕΙΑ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        try{
                                            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                                                $stmt = mysqli_prepare($dbcon, 'SELECT e.id_users, e.firstname, e.lastname, e.username, e.email,
                                                                             e.id_oplo, a.oplo_name, e.id_vathmos, a.vathmos_name,
                                                                              a.rolos_name, e.id_monada, a.monada_name 
                                                                             FROM users e 
                                                                             INNER JOIN active_user_info a ON e.id_users=a.id_users 
                                                                             WHERE a.id_users NOT IN(1, 2)');
                                                mysqli_stmt_execute($stmt);
                                                mysqli_stmt_store_result($stmt);
                                                mysqli_stmt_bind_result($stmt, $id_users, $firstname, $lastname, $username, 
                                                                        $email, $id_oplo, $oplo_name, $id_vathmos, $vathmos_name, 
                                                                         $rolos_name, $id_monada, $monada_name);
                                                while (mysqli_stmt_fetch($stmt)){
                                                    print "<tr>\n";
                                                    print "<td>$id_users</td>\n";
                                                    print "<td>$email</td>\n";
                                                    print "<td>$vathmos_name</td>\n";
                                                    print "<td>$oplo_name</td>\n";
                                                    print "<td>$firstname</td>\n";
                                                    print "<td>$lastname</td>\n";
                                                    print "<td>$rolos_name</td>\n";
                                                    print "<td>$monada_name</td>\n";
                                                    print "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' 
                                                            data-bs-target='#manage_user$id_users'>Επεξεργασία</button></td>\n";
                                                    
                                                    ?>
                                                    <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΕΠΕΞΕΡΓΑΣΙΑΣ)-->
                                                    <div class="modal fade" id="manage_user<?php print $id_users; ?>" tabindex="-1" aria-labelledby="manage_user<?php print $id_users; ?>" aria-hidden="true">
                                                        <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Επεξεργασία χρήστη</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="post" action="noview_changeuser.php">
                                                            <div class="modal-body">
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" name="lastname" value="<?php echo $lastname?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                                                                    <label for="floatingText">Επίθετο (*)</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" name="firstname" value="<?php echo $firstname?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                                                                    <label for="floatingText">Όνομα (*)</label>
                                                                </div>
                                                                <select class="form-select mb-3" name="id_vathmos" aria-label="Default select example" required>
                                                                    <option>Βαθμός (*)</option>
                                                                    <?php dropdown_menus_by_table('vathmos', $dbcon, $id_vathmos); ?>
                                                                </select>
                                                                <select class="form-select mb-3" name="id_oplo" aria-label="Default select example" required>
                                                                    <option>Ειδικότητα (*)</option>
                                                                    <?php dropdown_menus_by_table('oplo', $dbcon, $id_oplo); ?>
                                                                </select>
                                                                <select class="form-select mb-3" name="id_monada" aria-label="Default select example" required>
                                                                    <option>Μονάδα (*)</option>
                                                                    <?php dropdown_menus_by_table('monada', $dbcon, $id_monada); ?>
                                                                </select>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" name="username" value="<?php echo $username?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                                                                    <label for="floatingText">Όνομα χρήστη (*)</label>
                                                                <div class="form-floating mb-3">
                                                                    <input type="email" name="email" value="<?php echo $email?>" class="form-control" id="floatingInput" placeholder="name@example.com">
                                                                    <label for="floatingInput">Email address</label>
                                                                </div>
                                                                <input type="hidden"name="id_users" value="<?php print $id_users; ?>">
    
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Υποβολή</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                            </div>
                                                            </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toggle Form End -->
                                                    <?php
                                                    
                                                    print "<td><button type='button' class='btn btn-warning' data-bs-toggle='modal' 
                                                    data-bs-target='#reset_password$id_users'>Επαναφορά Κωδικού</button></td>\n";
                                                    ?>
                                                    <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON RESET PASSWORD)-->
                                                    <div class="modal fade" id="reset_password<?php print $id_users; ?>" tabindex="-1" aria-labelledby="reset_password<?php print $id_users; ?>" aria-hidden="true">
                                                        <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Επαναφορά Κωδικού Χρήστη</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form method="post" action="noview_resetpassword.php">
                                                                <div class="modal-body"> 
                                                                    <div class='text-danger'>Είστε σίγουρος ότι θέλετε να επαναφέρετε τον κωδικό του χρήστη;</div>
                                                                    <input type="hidden" name="id_users" value="<?php print $id_users; ?>"> 
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-warning">Επαναφορά</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toggle Form End -->
    
                                                    
                                                    <?php
    
                                                    print "<td><button type='button' class='btn btn-danger' data-bs-toggle='modal' 
                                                                data-bs-target='#delete_user$id_users'>Διαγραφή</button></td>\n";
                                                    ?>
                                                    <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΔΙΑΓΡΑΦΗΣ)-->
                                                    <div class="modal fade" id="delete_user<?php print $id_users; ?>" tabindex="-1" aria-labelledby="delete_user<?php print $id_users; ?>" aria-hidden="true">
                                                        <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Διαγραφή χρήστη</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form method="post" action="noview_deleteuser.php">
                                                                <div class="modal-body"> 
                                                                    <div class='text-danger'>Είστε σίγουρος ότι θέλετε να διαγράψετε τον χρήστη;</div>
                                                                    <input type="hidden"name="id_users" value="<?php print $id_users; ?>"> 
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-danger">Διαγραφή</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toggle Form End -->
                                                    <?php
                                                    print "</tr>\n";
                                                } 
                                            }else{
                                                $id_users = filter_input(INPUT_POST, 'id_users', FILTER_VALIDATE_INT);
                                                $email = filter_input(INPUT_POST, 'email');
                                                $id_vathmos = filter_input(INPUT_POST, 'id_vathmos', FILTER_VALIDATE_INT);
                                                $id_oplo = filter_input(INPUT_POST, 'id_oplo', FILTER_VALIDATE_INT);
                                                $lastname = filter_input(INPUT_POST, 'lastname');
                                                $id_monada = filter_input(INPUT_POST, 'id_monada', FILTER_VALIDATE_INT);
    
                                                if (empty($id_users)) $id_users="id_users";
                                                if (empty($email)) $email="%"; else $email = $email."%";
                                                if (empty($id_vathmos)) $id_vathmos="id_vathmos";
                                                if (empty($id_oplo)) $id_oplo="id_oplo";
                                                if (empty($lastname)) $lastname="%"; else $lastname = $lastname."%";
                                                if (empty($id_monada)) $id_monada="id_monada";
    
    
                                                $query = "SELECT e.id_users, e.firstname, e.lastname, e.username, e.email,
                                                                e.id_oplo, a.oplo_name, e.id_vathmos, a.vathmos_name,
                                                                a.rolos_name, e.id_monada, a.monada_name 
                                                                FROM users e INNER JOIN active_user_info a ON e.id_users=a.id_users 
                                                                WHERE a.id_users NOT IN(1, 2) AND a.id_users IN (SELECT id_users FROM users 
                                                                        WHERE id_users=$id_users AND id_vathmos=$id_vathmos AND id_oplo=$id_oplo AND id_monada=$id_monada 
                                                                        AND email LIKE ? AND lastname LIKE ?)"; 
                                                $stmt = mysqli_prepare($dbcon, $query);
                                                mysqli_stmt_bind_param($stmt, 'ss', $email, $lastname); 
                                                mysqli_stmt_execute($stmt);
                                                mysqli_stmt_store_result($stmt);
                                                mysqli_stmt_bind_result($stmt, $id_users, $firstname, $lastname, $username, 
                                                                        $email, $id_oplo, $oplo_name, $id_vathmos, $vathmos_name, 
                                                                         $rolos_name, $id_monada, $monada_name);
                                                while (mysqli_stmt_fetch($stmt)){
                                                    print "<tr>\n";
                                                    print "<td>$id_users</td>\n";
                                                    print "<td>$email</td>\n";
                                                    print "<td>$vathmos_name</td>\n";
                                                    print "<td>$oplo_name</td>\n";
                                                    print "<td>$firstname</td>\n";
                                                    print "<td>$lastname</td>\n";
                                                    print "<td>$rolos_name</td>\n";
                                                    print "<td>$monada_name</td>\n";
                                                    print "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' 
                                                            data-bs-target='#manage_user$id_users'>Επεξεργασία</button></td>\n";
                                                    
                                                    ?>
                                                    <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΕΠΕΞΕΡΓΑΣΙΑΣ)-->
                                                    <div class="modal fade" id="manage_user<?php print $id_users; ?>" tabindex="-1" aria-labelledby="manage_user<?php print $id_users; ?>" aria-hidden="true">
                                                        <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Επεξεργασία χρήστη</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="post" action="noview_changeuser.php">
                                                            <div class="modal-body">
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" name="lastname" value="<?php echo $lastname?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                                                                    <label for="floatingText">Επίθετο (*)</label>
                                                                </div>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" name="firstname" value="<?php echo $firstname?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                                                                    <label for="floatingText">Όνομα (*)</label>
                                                                </div>
                                                                <select class="form-select mb-3" name="id_vathmos" aria-label="Default select example" required>
                                                                    <option>Βαθμός (*)</option>
                                                                    <?php dropdown_menus_by_table('vathmos', $dbcon, $id_vathmos); ?>
                                                                </select>
                                                                <select class="form-select mb-3" name="id_oplo" aria-label="Default select example" required>
                                                                    <option>Ειδικότητα (*)</option>
                                                                    <?php dropdown_menus_by_table('oplo', $dbcon, $id_oplo); ?>
                                                                </select>
                                                                <select class="form-select mb-3" name="id_monada" aria-label="Default select example" required>
                                                                    <option>Μονάδα (*)</option>
                                                                    <?php dropdown_menus_by_table('monada', $dbcon, $id_monada); ?>
                                                                </select>
                                                                <div class="form-floating mb-3">
                                                                    <input type="text" name="username" value="<?php echo $username?>" class="form-control" id="floatingText" placeholder="jhondoe" required>
                                                                    <label for="floatingText">Όνομα χρήστη (*)</label>
                                                                <div class="form-floating mb-3">
                                                                    <input type="email" name="email" value="<?php echo $email?>" class="form-control" id="floatingInput" placeholder="name@example.com">
                                                                    <label for="floatingInput">Email address</label>
                                                                </div>
                                                                <input type="hidden"name="id_users" value="<?php print $id_users; ?>">
    
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Υποβολή</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                            </div>
                                                            </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toggle Form End -->
                                                    <?php
                                                    
                                                    print "<td><button type='button' class='btn btn-warning' data-bs-toggle='modal' 
                                                    data-bs-target='#reset_password$id_users'>Επαναφορά Κωδικού</button></td>\n";
                                                    ?>
                                                    <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON RESET PASSWORD)-->
                                                    <div class="modal fade" id="reset_password<?php print $id_users; ?>" tabindex="-1" aria-labelledby="reset_password<?php print $id_users; ?>" aria-hidden="true">
                                                        <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Επαναφορά Κωδικού Χρήστη</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form method="post" action="noview_resetpassword.php">
                                                                <div class="modal-body"> 
                                                                    <div class='text-danger'>Είστε σίγουρος ότι θέλετε να επαναφέρετε τον κωδικό του χρήστη;</div>
                                                                    <input type="hidden" name="id_users" value="<?php print $id_users; ?>"> 
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-warning">Επαναφορά</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toggle Form End -->
    
                                                    
                                                    <?php
    
                                                    print "<td><button type='button' class='btn btn-danger' data-bs-toggle='modal' 
                                                                data-bs-target='#delete_user$id_users'>Διαγραφή</button></td>\n";
                                                    ?>
                                                    <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΔΙΑΓΡΑΦΗΣ)-->
                                                    <div class="modal fade" id="delete_user<?php print $id_users; ?>" tabindex="-1" aria-labelledby="delete_user<?php print $id_users; ?>" aria-hidden="true">
                                                        <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Διαγραφή χρήστη</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form method="post" action="noview_deleteuser.php">
                                                                <div class="modal-body"> 
                                                                    <div class='text-danger'>Είστε σίγουρος ότι θέλετε να διαγράψετε τον χρήστη;</div>
                                                                    <input type="hidden"name="id_users" value="<?php print $id_users; ?>"> 
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-danger">Διαγραφή</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Toggle Form End -->
                                                    <?php
                                                    print "</tr>\n";
                                                }
                                            }//if-else (GET if/ POST else)
                                        }catch (mysqli_sql_exception $e) {
                                            var_dump($e);
                                            $errorpage_url = "errorpage.php?e=" . urlencode($e);
                                            header("Location: $errorpage_url");
                                            exit();
                                        }// try-catch

                                    ?>
                                </tbody>
                            </table>

                            <?php mysqli_stmt_close($stmt); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->

<?php
require('../templates/footer.inc.php');
?>