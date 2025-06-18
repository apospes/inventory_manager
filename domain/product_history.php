<?php
$title = "Ενεργά Υλικά";
require('../templates/header.inc.php');
check_session();
$barcode = filter_input(INPUT_GET, 'barcode');
if ($_SERVER['REQUEST_METHOD'] == 'POST' || empty($barcode)){
    //ob_end_flush();
    header("Location: active_product_view.php");
    exit();
}
?>


<!-- Table Start -->
<div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 auto-width">
                            <h6 class="mb-4">ΙΣΤΟΡΙΚΟ ΤΟΥ ΥΛΙΚΟΥ ΜΕ BARCODE <?php print "$barcode" ?></h6>
                            <table class="table table-striped text-center custom-table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">ΠΡΟΗΓΟΥΜΕΝΗ ΜΟΝΑΔΑ</th>
                                        <th scope="col">ΗΜ/ΝΙΑ ΧΡΕΩΣΗΣ</th>
                                        <th scope="col">ΕΜ/ΝΙΑ ΞΕΧΡΕΩΣΗΣ</th>
                                        <th scope="col">ΕΠΟΜΕΝΗ ΜΟΝΑΔΑ</th>
                                        <th scope="col">ΠΕΡΙΓΡΑΦΗ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        try{
                                            $monadaid = $_SESSION['id_monada'];
                                            $stmt = mysqli_prepare($dbcon, 'SELECT * FROM product_history_view WHERE barcode=? AND id_monada=?;');
                                            mysqli_stmt_bind_param($stmt, 'ii', $barcode, $monadaid);
                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_store_result($stmt);
                                            $count = mysqli_stmt_num_rows($stmt);
                                            if ($count <= 1) call_errorpage("Δεν βρέθηκε ιστορικό, που αφορά στη Μονάδα σας, για το συγκεκριμένο υλικό");
                                            $row = [];
                                            mysqli_stmt_bind_result($stmt, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5],
                                                                    $row[6], $row[7]);
                                            $aa = 1;
                                            while (mysqli_stmt_fetch($stmt)){
                                                print "<tr>\n";
                                                print "<th scope='row'>$aa</th>\n";
                                                print "<td>$row[1]</td>\n";
                                                print "<td>$row[2]</td>\n";
                                                print "<td>$row[4]</td>\n";
                                                print "<td>$row[5]</td>\n";
                                                print "<td class='text-truncate' title='$row[7]'>$row[7]</td>\n";
                                                print "</tr>\n";
                                                $aa = $aa + 1;
                                            }
                                            mysqli_stmt_close($stmt);
                                        }catch (mysqli_sql_exception $e) {
                                            var_dump($e);
                                            $errorpage_url = "errorpage.php?e=" . urlencode($e);
                                            header("Location: $errorpage_url");
                                            exit();
                                        }// try-catch

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->

<?php
require('../templates/footer.inc.php');
?>