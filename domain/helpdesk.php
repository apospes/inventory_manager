<?php
$title = "Help Desk";
require('../templates/header.inc.php');
//check_no_session();
?>

            <div class="container-fluid pt-4 px-4">
                <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    
                    <div class="col-md-6 text-center">
                    <h2 class="text-center">Help Desk</h2>
                    
                            <?php
                            // $msg = (string) isset($_GET['msg']);
                            // if ($msg == 'pass') {
                            //     print "\t<div class='alert alert-warning' role='alert'>\n\t";
                            //     print "Για ανάκτηση του κωδικού σας, παρακαλώ επικοινωνήστε με τον Διαχειριστή.\n\t</div>\n";
                            // } elseif ($msg == 'login') {
                            //     print "\t<div class='alert alert-warning' role='alert'>\n\t";
                            //     print "Ο διαχειριστής δεν έχει ακόμα αποδεκτεί το αίτημά σας.\n\t</div>\n";
                            // } elseif ($msg == 'success_register') {
                            //     print "\t<div class='alert alert-success' role='alert'>\n\t";
                            //     print "Επιτυχής εγγραφή! Αναμένεται τον διαχειριστή να αποδεκτεί το αίτημά σας.\n\t</div>\n";
                            // }

                            if (isset($_GET['msg'])) {
                                $msg = $_GET['msg'];

                                switch ($msg) {
                                    case "'pass'":
                                        echo "<div class='alert alert-warning' role='alert'>Για ανάκτηση του κωδικού σας, παρακαλώ επικοινωνήστε με τον Διαχειριστή.</div>";
                                        break;
                                    case "'login'":
                                        echo "<div class='alert alert-warning' role='alert'>Ο διαχειριστής δεν έχει ακόμα αποδεκτεί το αίτημά σας.</div>";
                                        break;
                                    case "'success_register'":
                                        echo "<div class='alert alert-success' role='alert'>Επιτυχής εγγραφή! Αναμένεται τον διαχειριστή να αποδεκτεί το αίτημά σας.</div>";
                                        break;
                                }
                            }
                            ?>
                        
                            <div class="alert alert-warning" role="alert">
                                Παρακαλούμε επικοινωνήστε με την υπηρεσία Υποστήριξης Χρηστών (Help Desk), στα τηλέφωνα:<br> ΕΨΑΔ: 800-8000 ΟΤΕ: 210-569-8000.
                            </div>
           
                        <?php
                        
                        print "\t<p><a href='index.php'>Πατήστε εδώ για την αρχική σελίδα.</a></p>\n";
                        ?>

                    </div>
                </div>
            </div>





<?php


require('../templates/footer.inc.php');
?>