<?php
$title = "Καταχώριση Υλικών";
require('../templates/header.inc.php');
check_session();
check_no_admin_users();
?>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-sm-12 col-xl-8">
            <div class="bg-light rounded h-100 p-4">
            <h3 class="mb-4">Μαζική Καταχώριση Υλικών</h3>

            <div class="row mb-3">
                <p><a href="../includes/template.xlsx" download><i class="fa fa-download"></i> Κατεβάστε το πρότυπο αρχείο.</a></p>
            </div>

            <form method="post" action="noview_massupload.php" enctype="multipart/form-data">
            <div class="row mb-3">
                <label for="csv_file" class="form-label">Επιλέξτε το αρχείο για υποβολή:</label>
                <input class="form-control" type="file" name="csv_file" id="csv_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Υποβολή <i class="fa fa-upload"></i></button>
            </form>

            </div>
        </div>
    </div>
</div>































<?php
require('../templates/footer.inc.php');
?>