<?php
$title = "Πίνακας Ελέγχου";
require('../templates/header.inc.php');
check_session();
check_admin_users();

try{
    $stmt = mysqli_prepare($dbcon, 'SELECT count(id_users) FROM users WHERE energos=1 AND id_rolos NOT IN(0,1);');
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $activeUserCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($dbcon, 'SELECT count(id_users) FROM users WHERE energos=0 AND id_rolos NOT IN(0,1);');
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $deactiveUserCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}// try-catch 

?>

 <!-- Panel Start -->
 <div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3 auto-width">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="bi bi-people fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2 text-truncate">Εγγεγραμένοι χρήστες</p>
                    <h6 class="text-primary h4 text-center"><?php print $activeUserCount; ?></h6>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 auto-width">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="bi-person-plus fa-3x text-danger"></i>
                <div class="ms-3">
                    <p class="mb-2 text-truncate">Εκκρεμή αιτήματα χρηστών</p>
                    <h6 class="text-primary h4 text-center"><?php print $deactiveUserCount; ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Panel  End -->


<?php
require('../templates/footer.inc.php');
?>