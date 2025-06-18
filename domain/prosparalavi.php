<?php
$title = "Προς Παραλαβη";
require('../templates/header.inc.php');
check_session();
check_no_admin_users();
?>
            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 auto-width">
                            <h3 class="mb-4">ΥΛΙΚΑ ΠΡΟΣ ΠΑΡΑΛΑΒΗ</h3>

                            <div id="search">
                                <form method="post" action="" class="form-inline"> <!--ΓΙΑ ΑΝΑΖΗΤΗΣΗ-->
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control mb-3" name="barcode" placeholder="Barcode">
                                        <input type="text" class="form-control mb-3" name="typos" placeholder="Τύπος">

                                        <select class="form-select mb-3" name="id_katigoria_prod">
                                            <option value="">Κατηγορία</option>
                                            <?php dropdown_menus_by_table('katigoria_prod', $dbcon); ?>
                                        </select>

                                        <select class="form-select mb-3" name="id_kataskeuastis">
                                            <option value="">Κατασκευαστής</option>
                                            <?php dropdown_menus_by_table('kataskeuastis', $dbcon); ?>
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
                                            <?php $id_tmima=null; dropdown_menus_by_table('tmima', $dbcon, $id_tmima, $_SESSION['id_monada'])  ?>
                                        </select> 

                                        <select class="form-select mb-3" name="id_diktyo">
                                            <option value="">Δίκτυο</option>
                                            <?php dropdown_menus_by_table('diktyo', $dbcon); ?>
                                        </select>
                                    </div>
                                    
                                    <p><button type="submit" class="btn btn-primary"><i class="fa fa-search me-2"></i>Αναζήτηση με χρήση των παραπάνω φίλτρων</button></p>
                                </form>
                            </div>

                                <?php //ΔΗΜΙΟΥΡΓΙΑ ΠΙΝΑΚΑ ΜΕ ΤΑ ID ΤΩΝ ΜΟΝΑΔΩΝ ΠΟΥ ΕΚΚΡΕΜΟΥΝ ΧΡΕΩΣΕΙΣ

                                try{
                                    $monadaid = $_SESSION['id_monada'];
                                    $stmtdistcmon = mysqli_prepare($dbcon, "SELECT distinct id_monada_before FROM diaxirisi.ekkremeis_xreoseis where id_monada_after= $monadaid");
                                    mysqli_stmt_execute($stmtdistcmon);
                                    mysqli_stmt_store_result($stmtdistcmon);
                                    $distmonades = [];
                                    mysqli_stmt_bind_result($stmtdistcmon, $dmon);
                                    $summon = 1;
                                    while (mysqli_stmt_fetch($stmtdistcmon)){
                                        $distmonades[$summon] = $dmon;
                                        $summon++ ;
                                    }
                                    mysqli_stmt_close($stmtdistcmon);
                                } catch (mysqli_sql_exception $e) {
                                    var_dump($e);
                                    $errorpage_url = "errorpage.php?e=" . urlencode($e);
                                    header("Location: $errorpage_url");
                                    exit();
                                }
                                $j=1;
                                do {
                                ?>

                                <form method="post" action="noview_apantisiaitimatos.php" class="form-inline"> <!--ΓΙΑ ΑΚΥΡΩΣΗ ΑΙΤΗΜΑΤΟΣ-->
                                <table class="table table-striped text-center custom-table align-middle" id="table_<?php echo $j; ?>" style="display: block; max-height: 450px;  overflow-y: scroll">
                                <thead style="position: sticky; top:0">
                                    <tr class="bg-light">
                                        <th scope="col"><input class='form-check-input' type='checkbox' onchange="checkAll('table_<?php echo $j; ?>', this)"></th>
                                        <th scope="col">ΑΠΟ</th>
                                        <th scope="col">BARCODE</th>
                                        <th scope="col">ΤΥΠΟΣ</th>
                                        <th scope="col">ΚΑΤΗΓΟΡΙΑ</th>
                                        <th scope="col">ΚΑΤΑΣΚΕΥΑΣΤΗΣ</th>
                                        <th scope="col">ΜΕΡΙΔΑ</th>
                                        <th scope="col">ΠΑΡΑΤΗΡΗΣΕΙΣ</th>
                                        <th scope="col">ΛΕΙΤΟΥΡΓΙΚΟ</th>
                                        <th scope="col">ΧΡΕΩΜΕΝΟ</th>
                                        <th scope="col">S/N</th>
                                        <th scope="col">ΗΜΕΡΟΜΗΝΙΑ ΧΡΕΩΣΗΣ</th>
                                        <!-- <th scope="col">ΜΟΝΑΔΑ</th> -->
                                        <th scope="col">ΤΜΗΜΑ</th>
                                        <th scope="col">ΔΙΚΤΥΟ</th>
                                        <!-- <th scope="col">ΙΣΤΟΡΙΚΟ</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($_SERVER['REQUEST_METHOD'] == 'GET'){
                                            $monadaid = $_SESSION['id_monada'];
                                            $stmt = mysqli_prepare($dbcon, 'SELECT * from (not_deleted_product_info p inner join ekkremeis_xreoseis ex on p.barcode=ex.barcode) inner join monada m on ex.id_monada_before=m.id_monada where id_monada_after=? and id_monada_before=?');
                                            mysqli_stmt_bind_param($stmt, 'ii', $monadaid, $distmonades[$j]);
                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_store_result($stmt);
                                            $row = [];
                                            mysqli_stmt_bind_result($stmt, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5],
                                                                    $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13] , $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);
                                            $aa = 1;
                                            while (mysqli_stmt_fetch($stmt)){
                                                print "<tr>\n";
                                                $help = "barcode_".$aa;
                                                print "<th scope='row'><input class='form-check-input' name='$help' value='$row[0]' type='checkbox'></th>\n";
                                                print "<td>\n";
                                                print "$row[19]\n";
                                                print "</td>\n";
                                                print "<td>\n";
                                                //print "<a class='btn btn-link' href='formproduct.php?barcode=$row[0]'>$row[0]</a>\n";
                                                print "$row[0]\n";

                                                print "</td>\n";
                                                if ($row[6] == 1) $row[6]="Ναι"; else $row[6]="Όχι";
                                                if ($row[7] == 1) $row[7]="Ναι"; else $row[7]="Όχι";
                                                for ($count = 1; $count<13; $count++){
                                                    if($count == 5 ){
                                                        print "<td class='text-truncate' title='$row[$count]'>$row[$count]</td>\n";
                                                    } elseif($count == 10){//ΓΙΑ ΝΑ ΠΕΤΑΞΟΥΜΕ ΕΞΩ ΤΗΝ ΜΟΝΑΔΑ
                                                        null;
                                                    }else{
                                                        print "<td>$row[$count]</td>\n";
                                                    }                                           
                                                }
                                                // print "<td>\n";
                                                // print "<button type='button' class='btn btn-link'
                                                //         value='$row[0]' onclick='showHistory(this, this.value, $aa)'>Έλεγχος</button>\n";
                                                // print "<div id='txtHint$aa'></div>";
                                                // print "</td>\n";
                                                print "</tr>\n";
                                                $aa = $aa + 1;
                                            }
                                        }else{
                                            $monadaid = $_SESSION['id_monada'];
                                            $barcode = filter_input(INPUT_POST, 'barcode', FILTER_VALIDATE_INT);
                                            $typos = filter_input(INPUT_POST, 'typos');
                                            $id_katigoria_prod = filter_input(INPUT_POST, 'id_katigoria_prod', FILTER_VALIDATE_INT);
                                            $id_kataskeuastis = filter_input(INPUT_POST, 'id_kataskeuastis', FILTER_VALIDATE_INT);
                                            $merida = filter_input(INPUT_POST, 'merida');
                                            $leitourgiko = filter_input(INPUT_POST, 'leitourgiko', FILTER_VALIDATE_INT);
                                            $xreomeno = filter_input(INPUT_POST, 'xreomeno', FILTER_VALIDATE_INT);
                                            $id_tmima = filter_input(INPUT_POST, 'id_tmima', FILTER_VALIDATE_INT);
                                            $id_diktyo = filter_input(INPUT_POST, 'id_diktyo', FILTER_VALIDATE_INT);

                                            if (empty($barcode)) $barcode="barcode";
                                            if (empty($id_katigoria_prod)) $id_katigoria_prod="id_katigoria_prod";
                                            if (empty($id_kataskeuastis)) $id_kataskeuastis="id_kataskeuastis"; 
                                            if (empty($merida)) $merida="merida";
                                            if (empty($leitourgiko)) $leitourgiko="leitourgiko";
                                            if (empty($xreomeno)) $xreomeno="xreomeno";
                                            if (empty($id_tmima)) $id_tmima="id_tmima";
                                            if (empty($id_diktyo)) $id_diktyo="id_diktyo";
                                            if (empty($typos)) $typos = "%"; else $typos = $typos."%";
                                            
                                            $query = "SELECT * FROM (not_deleted_product_info p inner join ekkremeis_xreoseis ex on p.barcode=ex.barcode) inner join monada m on ex.id_monada_before=m.id_monada 
                                                    where id_monada_after=? and id_monada_before=? AND (p.barcode IN (SELECT pr.barcode FROM products pr WHERE id_tmima IN (SELECT id_tmima FROM tmima WHERE id_monada=?)
                                                    AND barcode=$barcode AND typos LIKE ? AND id_katigoria_prod=$id_katigoria_prod 
                                                    AND id_kataskeuastis=$id_kataskeuastis AND merida=$merida AND leitourgiko=$leitourgiko 
                                                    AND xreomeno=$xreomeno AND id_tmima=$id_tmima AND id_diktyo=$id_diktyo));";
                                            $stmt = mysqli_prepare($dbcon, $query);
                                            mysqli_stmt_bind_param($stmt, 'iiis', $monadaid, $distmonades[$j], $distmonades[$j], $typos);  
                                            

                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_store_result($stmt);
                                            $row = [];
                                            mysqli_stmt_bind_result($stmt, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5],
                                                                    $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12] , $row[13] , $row[14], $row[15], $row[16], $row[17], $row[18], $row[19]);
                                            $aa = 1;
                                            while (mysqli_stmt_fetch($stmt)){
                                                print "<tr>\n";
                                                $help = "barcode_".$aa;
                                                print "<th scope='row'><input class='form-check-input' name='$help' value='$row[0]' type='checkbox'></th>\n";
                                                print "<td>\n";
                                                print "$row[19]\n";
                                                print "</td>\n";
                                                print "<td>\n";
                                                //print "<a class='btn btn-link' href='formproduct.php?barcode=$row[0]'>$row[0]</a>\n";
                                                print "$row[0]\n";

                                                print "</td>\n";
                                                if ($row[6] == 1) $row[6]="Ναι"; else $row[6]="Όχι";
                                                if ($row[7] == 1) $row[7]="Ναι"; else $row[7]="Όχι";
                                                for ($count = 1; $count<13; $count++){
                                                    if($count == 5){
                                                        print "<td class='text-truncate' title='$row[$count]'>$row[$count]</td>\n";
                                                    } elseif($count == 10){//ΓΙΑ ΝΑ ΠΕΤΑΞΟΥΜΕ ΕΞΩ ΤΗΝ ΜΟΝΑΔΑ
                                                        null;
                                                    }else{
                                                        print "<td>$row[$count]</td>\n";
                                                    }                                           
                                                }
                                                // print "<td>\n";
                                                // print "<button type='button' class='btn btn-link'
                                                //         value='$row[0]' onclick='showHistory(this, this.value, $aa)'>Έλεγχος</button>\n";
                                                // print "<div id='txtHint$aa'></div>";
                                                // print "</td>\n";
                                                print "</tr>\n";
                                                $aa = $aa + 1;
                                            }
                                        }//if-else (method is get/post)
                                    ?>
                                </tbody>
                            </table>
                                    <input hidden name='aa' value='<?php echo $aa; ?>'></input>
                                    <?php mysqli_stmt_close($stmt); ?>
                                    <label class="text-primary"><?php print "$row[19] : " ?></label>
                                    <button type="submit" name="apodoxi_button" class="btn btn-success" <?php if ($aa==1){echo 'disabled';} ?>><i class="fa fa-check-circle me-2"></i>Αποδοχή Παραλαβής</button>
                                    <button type="submit" name="aporripsi_button" class="btn btn-danger" <?php if ($aa==1){echo 'disabled';} ?>><i class="fa fa-times-circle me-2"></i>Απόρριψη Παραλαβής</button>
                                    </form>
                            <!-- <button onclick="downloadTableAsExcel()" class="btn btn-success">Download as Excel</button> -->
                            <?php 
                                $j++;} while ($j<$summon); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->

            <!-- ΓΙΑ ΕΠΙΛΟΓΗ ΚΑΙ ΑΦΑΙΡΕΣΗ ΕΠΙΛΟΓΗΣ ΟΛΩΝ ΤΩΝ CHECKBOXIES -->
            <!-- <script>
            let checkboxes = document.querySelectorAll("input[type = 'checkbox']");
            function checkAll(myCheckBox) {
                if (myCheckBox.checked == true) {
                    checkboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                    });
                } else {
                    checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    });
                }
            }
            </script> -->
            <!-- ΓΙΑ ΕΠΙΛΟΓΗ ΚΑΙ ΑΦΑΙΡΕΣΗ ΕΠΙΛΟΓΗΣ ΟΛΩΝ ΤΩΝ CHECKBOXIES ΓΙΑ ΠΟΛΛΟΥΣ ΠΙΝΑΚΕΣ ΣΤΗΝ ΙΔΙΑ ΣΕΛΙΔΑ -->
            <script>
                function checkAll(tableId, masterCheckbox) {
                    let table = document.getElementById(tableId);
                    let checkboxes = table.querySelectorAll("input[type='checkbox']");
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = masterCheckbox.checked;
                    });
                }
            </script>



<?php
require('../templates/footer.inc.php');
?>