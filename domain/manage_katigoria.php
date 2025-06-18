<?php
$title = "Διαχείριση Κατηγοριών";
require('../templates/header.inc.php');
check_session();
check_admin_users();
?>
            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 auto-width">
                            <h3 class="mb-4">ΟΛΕΣ ΟΙ ΚΑΤΗΓΟΡΙΕΣ</h3>

                            <!-- <div id="search">
                                <form method="post" action="" class="form-inline"> ΓΙΑ ΑΝΑΖΗΤΗΣΗ
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control mb-3" name="barcode" placeholder="Barcode">
                                        <input type="text" class="form-control mb-3" name="typos" placeholder="Τύπος">

                                        <select class="form-select mb-3" name="id_katigoria_prod">
                                            <option value="">Κατηγορία</option>
                                            <?php //dropdown_menus_by_table('katigoria_prod', $dbcon); ?>
                                        </select>

                                        <select class="form-select mb-3" name="id_kataskeuastis">
                                            <option value="">Κατασκευαστής</option>
                                            <?php //dropdown_menus_by_table('kataskeuastis', $dbcon); ?>
                                        </select>

                                        <input type="text" class="form-control mb-3" name="merida" placeholder="Μερίδα">

                                        <select class="form-select mb-3" name="leitourgiko">
                                            <option selected>Λειτουργικό</option>
                                            <option value="1">Ναι</option>
                                            <option value="0">Όχι</option>
                                        </select>

                                        <select class="form-select mb-3" name="xreomeno">
                                            <option selected>Χρεωμένο</option>
                                            <option value="1">Ναι</option>
                                            <option value="0">Όχι</option>
                                        </select>

                                        <select class="form-select mb-3" id="id_tmima" name="id_tmima">
                                            <option value="">Τμήμα</option>
                                            <?php //$id_tmima=null; dropdown_menus_by_table('tmima', $dbcon, $id_tmima, $_SESSION['id_monada'])  ?>
                                        </select> 

                                        <select class="form-select mb-3" name="id_diktyo">
                                            <option value="">Δίκτυο</option>
                                            <?php //dropdown_menus_by_table('diktyo', $dbcon); ?>
                                        </select>
                                    </div>
                                    
                                    <p><button type="submit" class="btn btn-primary"><i class="fa fa-search me-2"></i>Αναζήτηση με χρήση των παραπάνω φίλτρων</button></p>
                                </form>
                            </div> -->





                                <table class="table table-striped text-center custom-table align-middle" style="display: block; max-height: 350px; overflow-y: scroll">
                                <thead style="position: sticky; top:0">
                                    <tr class="bg-light">
                                        <th scope="col">#</th>
                                        <th scope="col">ID ΚΑΤΗΓΟΡΙΑΣ</th>
                                        <th scope="col">ΚΑΤΗΓΟΡΙΑ</th>
                                        <th scope="col">ΕΠΕΞΕΡΓΑΣΙΑ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        try{
                                            if($_SERVER['REQUEST_METHOD'] == 'POST') call_errorpage("Λάθος τρόπος πρόσβασης της σελίδας");
                                            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                                                
                                                $stmt = mysqli_prepare($dbcon, 'SELECT * from katigoria_prod order by katigoria_prod_name');
                                                
                                                mysqli_stmt_execute($stmt);
                                                mysqli_stmt_store_result($stmt);
                                                mysqli_stmt_bind_result($stmt, $id_katigoria, $katigoria_name);
                                                $aa = 1;
                                                while (mysqli_stmt_fetch($stmt)){
                                                    print "<tr>\n";
                                                    
                                                    // print "<th scope='row'></th>\n";
                                                    print "<td>\n$aa\n</td>\n";
                                                    print "<td>\n$id_katigoria\n</td>\n";
                                                    print "<td>\n$katigoria_name\n</td>\n";
                                                    print "<td>\n<button type='button' class='btn btn-primary' data-bs-toggle='modal' 
                                                            data-bs-target='#change_katigoria$id_katigoria'>Επεξεργασία</button></td>";
                                        ?>
                                            <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΤΡΟΠΟΠΟΙΗΣΗ)-->
                                            <div class="modal fade" id="change_katigoria<?php print $id_katigoria; ?>" tabindex="-1" aria-labelledby="change_katigoria<?php print $id_katigoria; ?>" aria-hidden="true">
                                                <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Αλλαγή ονόματος κατηγορίας</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="post" action="noview_katigoria.php">
                                                    <div class="modal-body"> 
                                                        <div class="mb-3">
                                                            <label for="katigoria_name" class="col-form-label">Εισάγετε το νέο όνομα:</label>
                                                            <input type="text" class="form-control" name="katigoria_name">
                                                            <input type="hidden"name="id_katigoria" value="<?php print $id_katigoria; ?>">
                                                            <input type="hidden"name="action" value="1">
                                                        </div>     
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Υποβολή</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
                                                    </div>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            //<!-- Toggle Form End -->
                                                    print "</tr>\n";
                                                    $aa = $aa + 1;
                                                }// while
                                            }// if
                                        }catch (mysqli_sql_exception $e) {
                                            var_dump($e);
                                            $errorpage_url = "errorpage.php?e=" . urlencode($e);
                                            header("Location: $errorpage_url");
                                            exit();
                                        }// try-catch


                                        // else{
                                        //     $monadaid = $_SESSION['id_monada'];
                                        //     $barcode = filter_input(INPUT_POST, 'barcode', FILTER_VALIDATE_INT);
                                        //     $typos = filter_input(INPUT_POST, 'typos');
                                        //     $id_katigoria_prod = filter_input(INPUT_POST, 'id_katigoria_prod', FILTER_VALIDATE_INT);
                                        //     $id_kataskeuastis = filter_input(INPUT_POST, 'id_kataskeuastis', FILTER_VALIDATE_INT);
                                        //     $merida = filter_input(INPUT_POST, 'merida');
                                        //     $leitourgiko = filter_input(INPUT_POST, 'leitourgiko', FILTER_VALIDATE_INT);
                                        //     $xreomeno = filter_input(INPUT_POST, 'xreomeno', FILTER_VALIDATE_INT);
                                        //     $id_tmima = filter_input(INPUT_POST, 'id_tmima', FILTER_VALIDATE_INT);
                                        //     $id_diktyo = filter_input(INPUT_POST, 'id_diktyo', FILTER_VALIDATE_INT);

                                        //     if (empty($barcode)) $barcode="p.barcode";
                                        //     if (empty($id_katigoria_prod)) $id_katigoria_prod="p.id_katigoria_prod";
                                        //     if (empty($id_kataskeuastis)) $id_kataskeuastis="p.id_kataskeuastis"; 
                                        //     if (empty($merida)) $merida="merida";
                                        //     if (empty($leitourgiko)) $leitourgiko="leitourgiko";
                                        //     if (empty($xreomeno)) $xreomeno="xreomeno";
                                        //     if (empty($id_tmima)) $id_tmima="p.id_tmima";
                                        //     if (empty($id_diktyo)) $id_diktyo="p.id_diktyo";
                                        //     if (empty($typos)) $typos = "%"; else $typos = $typos."%";
                                            
                                        //     $query = "SELECT p.barcode, typos, k.katigoria_prod_name, ka.kataskeuastis_name, merida, 
                                        //                 paratiriseis,leitourgiko, xreomeno , sn, import_date, t.id_monada, t.tmima_name, d.diktyo_name 
                                        //                 , p.id_katigoria_prod, p.id_kataskeuastis, p.id_tmima, p.id_diktyo, t.id_monada

                                        //                 from products p inner join tmima t on p.id_tmima=t.id_tmima
                                        //                     inner join katigoria_prod k on p.id_katigoria_prod=k.id_katigoria_prod
                                        //                     inner join kataskeuastis ka on p.id_kataskeuastis=ka.id_kataskeuastis
                                        //                     inner join diktyo d on p.id_diktyo=d.id_diktyo
                                        //                     left join ekkremeis_xreoseis e on p.barcode=e.barcode
                                        //                         where diegrameno=0 and e.barcode is null
                                        //                         AND id_monada=?


                                        //             AND p.barcode=$barcode AND typos LIKE ? AND p.id_katigoria_prod=$id_katigoria_prod 
                                        //             AND p.id_kataskeuastis=$id_kataskeuastis AND merida=$merida AND leitourgiko=$leitourgiko 
                                        //             AND xreomeno=$xreomeno AND p.id_tmima=$id_tmima AND p.id_diktyo=$id_diktyo;";
                                        //     $stmt = mysqli_prepare($dbcon, $query);
                                        //     mysqli_stmt_bind_param($stmt, 'is' , $monadaid, $typos);  

                                        //     mysqli_stmt_execute($stmt);
                                        //     mysqli_stmt_store_result($stmt);
                                        //     $row = [];
                                        //     mysqli_stmt_bind_result($stmt, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5],
                                        //                             $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12] , $row[13], $row[14], $row[15], $row[16], $row[17]);
                                        //     $aa = 1;
                                        //     while (mysqli_stmt_fetch($stmt)){
                                        //         print "<tr>\n";
                                        //         $help = "barcode_".$aa;
                                        //         print "<th scope='row'><input class='form-check-input' name='$help' value='$row[0]' type='checkbox'></th>\n";
                                        //         print "<td>\n";
                                        //         //print "<a class='btn btn-link' href='formproduct.php?barcode=$row[0]'>$row[0]</a>\n";
                                        //         print "$row[0]\n";

                                        //         print "</td>\n";
                                        //         if ($row[6] == 1) $row[6]="Ναι"; else $row[6]="Όχι";
                                        //         if ($row[7] == 1) $row[7]="Ναι"; else $row[7]="Όχι";
                                        //         for ($count = 1; $count<13; $count++){
                                        //             if($count == 5){
                                        //                 print "<td class='text-truncate' title='$row[$count]'>$row[$count]</td>\n";
                                        //             } elseif($count == 10){//ΓΙΑ ΝΑ ΠΕΤΑΞΟΥΜΕ ΕΞΩ ΤΗΝ ΜΟΝΑΔΑ-tmimaid(to evala xwris logo gia 2i fora gia na min xalasw tin roi)
                                        //                 null;
                                        //             }else{
                                        //                 print "<td>$row[$count]</td>\n";
                                        //             }                                           
                                        //         }
                                        //         // print "<td>\n";
                                        //         // print "<button type='button' class='btn btn-link'
                                        //         //         value='$row[0]' onclick='showHistory(this, this.value, $aa)'>Έλεγχος</button>\n";
                                        //         // print "<div id='txtHint$aa'></div>";
                                        //         // print "</td>\n";
                                        //         print "</tr>\n";
                                        //         $aa = $aa + 1;
                                        //     }
                                        // }//if-else (method is get/post)
                                    ?>
                                </tbody>
                            </table>


                            <?php
                            print "<button type='button' class='btn btn-primary' data-bs-toggle='modal' 
                                    data-bs-target='#create_katigoria'>Προσθήκη Κατηγορίας</button>\n"; 
                            ?>
                            <!-- Toggle Form Start (ΓΙΑ ΤΟ BUTTON ΔΗΜΙΟΥΡΓΙΑ)-->
                            <div class="modal fade" id="create_katigoria" tabindex="-1" aria-labelledby="create_katigoria" aria-hidden="true">
                                <div class="modal-dialog position-absolute top-50 start-50 translate-middle">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Καταχώριση νέας κατηγορίας</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post" action="noview_katigoria.php">
                                    <div class="modal-body"> 
                                        <div class="mb-3">
                                            <label for="katigoria_name" class="col-form-label">Εισάγετε όνομα:</label>
                                            <input type="text" class="form-control" name="katigoria_name">
                                            
                                            <input type="hidden"name="action" value="3">
                                        </div>     
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
                                    
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->

<?php


mysqli_stmt_close($stmt);
require('../templates/footer.inc.php');
?>