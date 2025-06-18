<?php
$title = "Διαχείριση Χρηστών";
require('../templates/header.inc.php');
check_session();
if ($_SESSION['id_rolos'] != 2) call_errorpage('Σελίδα πρόσβασης μόνο για Γενικό Διαχειριστή Υλικού της Μονάδας!');
?>
            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 auto-width">
                            <?php
                                $id_monada = $_SESSION['id_monada'];
                                $query = "SELECT monada_name FROM monada WHERE id_monada=$id_monada";
                                $stmt = mysqli_prepare($dbcon, $query);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_store_result($stmt);
                                mysqli_stmt_bind_result($stmt, $monada_name);
                                mysqli_stmt_fetch($stmt);
                            ?>
                            <h3 class="mb-4">ΤΜΗΜΑΤΑ ΤΗΣ ΜΟΝΑΔΑΣ <?php print $monada_name; ?></h3>

                            <table class="table table-striped text-center custom-table">
                                <thead>
                                    <tr>
                                        <th scope="col">A/A</th>
                                        <th scope="col">ID ΤΜΗΜΑΤΟΣ</th>
                                        <th scope="col">ΟΝΟΜΑ</th>
                                        <th scope="col" colspan="2">ΕΝΕΡΓΕΙΑ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        try{
                                            if($_SERVER['REQUEST_METHOD'] == 'POST') call_errorpage("Λάθος τρόπος πρόσβασης της σελίδας");
                                            $stmt = mysqli_prepare($dbcon, "SELECT id_tmima, tmima_name FROM tmima WHERE id_monada=$id_monada AND tmima_name != 'Γενική Διαχείριση';");
                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_store_result($stmt);
                                            mysqli_stmt_bind_result($stmt, $id_tmima, $tmima_name);
                                            $aa = 0;
                                            while (mysqli_stmt_fetch($stmt)){
                                                $aa = $aa + 1;
                                                print "<tr>\n";
                                                print "<th scope='row'>$aa</th>";
                                                print "<td>$id_tmima</td>\n";
                                                print "<td>$tmima_name</td>\n";
                                                print "<td><button type='button' class='btn btn-primary' data-bs-toggle='modal' 
                                                            data-bs-target='#change_tmima$id_tmima'>Τροποποίηση</button>";
                                        ?>
                                            <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΤΡΟΠΟΠΟΙΗΣΗ)-->
                                            <div class="modal fade" id="change_tmima<?php print $id_tmima; ?>" tabindex="-1" aria-labelledby="change_tmima<?php print $id_tmima; ?>" aria-hidden="true">
                                                <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Αλλαγή ονόματος τμήματος</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="post" action="noview_tmima.php">
                                                    <div class="modal-body"> 
                                                        <div class="mb-3">
                                                            <label for="tmima_name" class="col-form-label">Εισάγετε το νέο όνομα:</label>
                                                            <input type="text" class="form-control" name="tmima_name">
                                                            <input type="hidden"name="id_tmima" value="<?php print $id_tmima; ?>">
                                                            <input type="hidden"name="action" value="1">
                                                        </div>     
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Υποβολή</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Toggle Form End -->
                                        <?php
                                                print "<td><button type='button' class='btn btn-danger' data-bs-toggle='modal' 
                                                        data-bs-target='#delete_tmima$id_tmima'>Διαγραφή</button></td>\n";
                                                        ?>
                                                        
                                                        <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΔΙΑΓΡΑΦΗΣ)-->
                                                        <div class="modal fade" id="delete_tmima<?php print $id_tmima; ?>" tabindex="-1" aria-labelledby="delete_tmima<?php print $id_tmima; ?>" aria-hidden="true">
                                                            <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                                <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Διαγραφή τμήματος</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form method="post" action="noview_tmima.php">
                                                                <div class="modal-body"> 
                                                                    <div class='text-danger'>Είστε σίγουρος ότι θέλετε να διαγράψετε το τμήμα;</div>
                                                                    <input type="hidden"name="id_tmima" value="<?php print $id_tmima; ?>"> 
                                                                    <input type="hidden"name="action" value="2">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Διαγραφή</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                                </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Toggle Form End -->
                                                            <?php
                                                print "</tr>\n";
                                            } // while
                                        }catch (mysqli_sql_exception $e) {
                                            var_dump($e);
                                            $errorpage_url = "errorpage.php?e=" . urlencode($e);
                                            header("Location: $errorpage_url");
                                            exit();
                                        }// try-catch
                                    ?>
                                </tbody>
                            </table>
                            

                            <?php
                            print "<button type='button' class='btn btn-primary' data-bs-toggle='modal' 
                                    data-bs-target='#create_tmima'>Δημιουργία Νέου</button>\n"; 
                            ?>
                                                                <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΔΗΜΙΟΥΡΓΙΑ)-->
                            <div class="modal fade" id="create_tmima" tabindex="-1" aria-labelledby="create_tmima" aria-hidden="true">
                                <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Δημιουργία νέου τμήματος</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post" action="noview_tmima.php">
                                    <div class="modal-body"> 
                                        <div class="mb-3">
                                            <label for="tmima_name" class="col-form-label">Εισάγετε όνομα:</label>
                                            <input type="text" class="form-control" name="tmima_name">
                                            <input type="hidden"name="id_monada" value="<?php print $id_monada; ?>">
                                            <input type="hidden"name="action" value="3">
                                        </div>     
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Υποβολή</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Toggle Form End -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->



<?php
mysqli_stmt_close($stmt);
require('../templates/footer.inc.php');
?>