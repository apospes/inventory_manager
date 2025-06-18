<?php
$title = "Χρέωση Υλικών";
require('../templates/header.inc.php');
check_session();
check_no_admin_users();
?>
            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 auto-width">
                            <h3 class="mb-4">ΟΛΑ ΤΑ ΥΛΙΚΑ</h3>

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






                                <form method="post" action="noview_xreostika.php" class="form-inline"> <!--ΓΙΑ ΔΗΜΙΟΥΡΓΙΑ ΧΡΕΩΣΤΙΚΟΥ-->
                                <table class="table table-striped text-center custom-table align-middle" id="downloadable" style="display: block; max-height: 350px; overflow-y: scroll">
                                <thead style="position: sticky; top:0">
                                    <tr class="bg-light">
                                        <th scope="col"><input class='form-check-input' type='checkbox' onchange="checkAll(this)"></th>
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
                                            $stmt = mysqli_prepare($dbcon, 'CALL get_pros_xreosi_product_info_by_monada(?)');
                                            mysqli_stmt_bind_param($stmt, 'i', $monadaid);
                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_store_result($stmt);
                                            $row = [];
                                            mysqli_stmt_bind_result($stmt, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5],
                                                                    $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17]);
                                            $aa = 1;
                                            while (mysqli_stmt_fetch($stmt)){
                                                print "<tr>\n";
                                                $help = "barcode_".$aa;
                                                print "<th scope='row'><input class='form-check-input' name='$help' value='$row[0]' type='checkbox'></th>\n";
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

                                            if (empty($barcode)) $barcode="p.barcode";
                                            if (empty($id_katigoria_prod)) $id_katigoria_prod="p.id_katigoria_prod";
                                            if (empty($id_kataskeuastis)) $id_kataskeuastis="p.id_kataskeuastis"; 
                                            if (empty($merida)) $merida="merida";
                                            if (empty($leitourgiko)) $leitourgiko="leitourgiko";
                                            if (empty($xreomeno)) $xreomeno="xreomeno";
                                            if (empty($id_tmima)) $id_tmima="p.id_tmima";
                                            if (empty($id_diktyo)) $id_diktyo="p.id_diktyo";
                                            if (empty($typos)) $typos = "%"; else $typos = $typos."%";
                                            
                                            $query = "SELECT p.barcode, typos, k.katigoria_prod_name, ka.kataskeuastis_name, merida, 
                                                        paratiriseis,leitourgiko, xreomeno , sn, import_date, t.id_monada, t.tmima_name, d.diktyo_name 
                                                        , p.id_katigoria_prod, p.id_kataskeuastis, p.id_tmima, p.id_diktyo, t.id_monada

                                                        from products p inner join tmima t on p.id_tmima=t.id_tmima
                                                            inner join katigoria_prod k on p.id_katigoria_prod=k.id_katigoria_prod
                                                            inner join kataskeuastis ka on p.id_kataskeuastis=ka.id_kataskeuastis
                                                            inner join diktyo d on p.id_diktyo=d.id_diktyo
                                                            left join ekkremeis_xreoseis e on p.barcode=e.barcode
                                                                where diegrameno=0 and e.barcode is null
                                                                AND id_monada=?


                                                    AND p.barcode=$barcode AND typos LIKE ? AND p.id_katigoria_prod=$id_katigoria_prod 
                                                    AND p.id_kataskeuastis=$id_kataskeuastis AND merida=$merida AND leitourgiko=$leitourgiko 
                                                    AND xreomeno=$xreomeno AND p.id_tmima=$id_tmima AND p.id_diktyo=$id_diktyo;";
                                            $stmt = mysqli_prepare($dbcon, $query);
                                            mysqli_stmt_bind_param($stmt, 'is' , $monadaid, $typos);  

                                            mysqli_stmt_execute($stmt);
                                            mysqli_stmt_store_result($stmt);
                                            $row = [];
                                            mysqli_stmt_bind_result($stmt, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5],
                                                                    $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12] , $row[13], $row[14], $row[15], $row[16], $row[17]);
                                            $aa = 1;
                                            while (mysqli_stmt_fetch($stmt)){
                                                print "<tr>\n";
                                                $help = "barcode_".$aa;
                                                print "<th scope='row'><input class='form-check-input' name='$help' value='$row[0]' type='checkbox'></th>\n";
                                                print "<td>\n";
                                                //print "<a class='btn btn-link' href='formproduct.php?barcode=$row[0]'>$row[0]</a>\n";
                                                print "$row[0]\n";

                                                print "</td>\n";
                                                if ($row[6] == 1) $row[6]="Ναι"; else $row[6]="Όχι";
                                                if ($row[7] == 1) $row[7]="Ναι"; else $row[7]="Όχι";
                                                for ($count = 1; $count<13; $count++){
                                                    if($count == 5){
                                                        print "<td class='text-truncate' title='$row[$count]'>$row[$count]</td>\n";
                                                    } elseif($count == 10){//ΓΙΑ ΝΑ ΠΕΤΑΞΟΥΜΕ ΕΞΩ ΤΗΝ ΜΟΝΑΔΑ-tmimaid(to evala xwris logo gia 2i fora gia na min xalasw tin roi)
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
                                    <div class="row mb-3">
                                        <label for="monada_pros" class="col-sm-2 col-form-label">Μοναδα προς χρέωση</label>
                                        <div class="col-sm-4">
                                            <select class="form-select mb-3" name="monada_pros" aria-label="Default select example" required>
                                            <option value="">Μονάδα (*)</option>
                                            <?php dropd_alles_monades($_SESSION['id_monada'], $dbcon); ?>
                                        </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="perigrafi" class="col-sm-2 col-form-label">Περιγραφή</label>
                                        <div class="col-sm-6">
                                            <textarea name="perigrafi" class="form-control" id="paratiriseis"></textarea>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success" <?php if ($aa==1){echo 'disabled';} ?>><i class="fa fa-reply me-2"></i>Αίτημα χρέωσης</button>
                                    </form>
                            <!-- <button onclick="downloadTableAsExcel()" class="btn btn-success">Download as Excel</button> -->
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table End -->

            <!-- ΓΙΑ ΕΠΙΛΟΓΗ ΚΑΙ ΑΦΑΙΡΕΣΗ ΕΠΙΛΟΓΗΣ ΟΛΩΝ ΤΩΝ CHECKBOXIES -->
            <script>
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
            </script>



<?php

function dropd_alles_monades($existmonada, $dbcon , $id_lastused=null){
    try{

        $qddam = "SELECT id_monada, monada_name FROM monada where id_monada!=? ";
        $stmtddam = mysqli_prepare($dbcon, $qddam);  
        mysqli_stmt_bind_param($stmtddam, 'i', $existmonada);
        

        mysqli_stmt_execute($stmtddam);
        mysqli_stmt_store_result($stmtddam);
        mysqli_stmt_bind_result($stmtddam, $idhf, $namehf);
        while (mysqli_stmt_fetch($stmtddam)) {
            $idhf =  intval(strip_tags($idhf));
            $namehf = strip_tags($namehf);
            if ($id_lastused == $idhf){
                print "<option value='$idhf' selected>$namehf</option>";
            } else {
                print "<option value='$idhf'>$namehf</option>";
            };
        }

        mysqli_stmt_close($stmtddam);
    }catch (mysqli_sql_exception $e) {
        var_dump($e);
        $errorpage_url = "errorpage.php?e=" . urlencode($e);
        header("Location: $errorpage_url");
        exit();
    }
}





require('../templates/footer.inc.php');
?>