<?php
$title = "Σελίδα Σφάλματος";
require('../templates/header.inc.php');
?>

            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="col-md-6 text-center">
                        <h2>Σφάλμα</h2>
                        <?php
                        if (isset($_GET['e'])) {
                            $e = urldecode($_GET['e']);
                            print "<h3>Το σφάλμα είναι το ακόλουθο:</h3>\n";
                        ?>

                            <div class="alert alert-danger" role="alert">
                            <?php print $e ?>
                            </div>

                        <?php
                        } else {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                Παρουσιάστηκε κάποιο απροσδιόριστο σφάλμα. Παρακαλώ προσπαθήστε ξανά!
                            </div>
           
                        <?php
                        }
                        print "\t<p><a href='index.php'>Πατήστε εδώ για την αρχική σελίδα.</a></p>\n";
                        ?>

                    </div>
                </div>
            </div>





<?php


require('../templates/footer.inc.php');
?>