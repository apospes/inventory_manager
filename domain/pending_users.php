<?php
$title = "Αιτήσεις Χρηστών";
require('../templates/header.inc.php');
check_session();
check_admin_users();

try{
    $stmt = mysqli_prepare($dbcon, 'SELECT * FROM all_user_info WHERE energos=0;');
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $id_users, $email, $firstname, $lastname, $oplo_name, $vathmos_name, $rolos_name, $monada_name, $energos);
?>
<div class="container-fluid pt-4 px-4 overflow-auto custom-table-border">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4 auto-width">
                <h3 class="mb-4">ΕΚΚΡΕΜΕΙΣ ΑΙΤΗΣΕΙΣ</h3>
                <table class="table table-striped text-center custom-table align-middle" id="downloadable" style="display: block; height: 450px; overflow-y: scroll">
                    <thead style="position: sticky; top:0">
                        <tr class="bg-light">
                            <th scope="col">#</th>
                            <th scope="col">USERID</th>
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
                            $aa = 1;
                            while (mysqli_stmt_fetch($stmt)){
                                print "<tr>\n";
                                print "<th>$aa</th>\n";
                                print "<td>$id_users</td>\n";
                                print "<td>$email</td>\n";
                                print "<td>$vathmos_name</td>\n";
                                print "<td>$oplo_name</td>\n";
                                print "<td>$firstname</td>\n";
                                print "<td>$lastname</td>\n";
                                print "<td>$rolos_name</td>\n";
                                print "<td>$monada_name</td>\n";
                                print "<td id='accept$aa'><button type='button' class='btn btn-link text-success'
                                        value='$id_users' onclick='userReply(this.value, 1, $aa)'>Αποδοχή</button></td>\n";
                                print "<td id='reject$aa'><button type='button' class='btn btn-link text-danger'
                                        value='$id_users' onclick='userReply(this.value, 0, $aa)'>Απόρριψη</button></td>\n";
                                print "<td id='txtHint$aa' colspan='2'></td>\n";
                                print "</tr>\n";
                                $aa = $aa + 1;
                            }    
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
}catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}// try-catch


mysqli_stmt_close($stmt);
require('../templates/footer.inc.php');
?>