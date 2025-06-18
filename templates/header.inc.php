<?php
    ob_start();
    session_start();
    date_default_timezone_set('Europe/Athens');
    
    require('../database/mysqli_con.php');
    require_once('../includes/helper_fun.inc.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php if (isset($title)) print $title; else print "Διαχείριση"; ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="../img/kepyes_logo.jpg" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .auto-width {
            width: auto;
            display: inline-block; /* Ensures the div only takes as much width as needed by its content */
        }
        .custom-table-border {
            border: 20px solid white; /* Adjust the width as needed */
        }

        .custom-table td, th {
            min-width: 0px; /* Adjust the width as needed */
            max-width: 250px; /* Adjust the max width as needed */
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->
        <?php
        if (is_loggedin()) { ?>
        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-folder-open me-2"></i>ΔΙΑΧΕΙΡΙΣΗ</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $_SESSION['lastname'],' ', $_SESSION['firstname'];?></h6>
                        <span><?php echo print_by_id('monada' , $_SESSION['id_monada'], $dbcon)?></span>
                    </div>
                </div>
                
                <div class="navbar-nav w-100">
                    <?php if ($_SESSION['id_rolos'] == 1 || $_SESSION['id_rolos'] == 0) { ?>
                    <a href="control_panel.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Πίνακας Ελέγχου</a>
                    <a href="charts.php" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Στατιστικά</a>
                    <?php }//ΕΑΝ ΕΙΝΑΙ ΕΝΑΣ ΑΠΟ ΤΟΥΣ ΔΥΟ ADMINS ?>


                    <?php if ($_SESSION['id_rolos'] != 0) { //ΔΕΝ ΕΧΕΙ ΠΡΟΣΒΑΣΗ Ο ADMIN ΤΩΝ ΧΡΗΣΤΩΝ ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-plus-square me-2"></i>Καταχώριση</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="formproduct.php" class="dropdown-item">Φόρμα Υλικού</a>
                            <a href="massproduct.php" class="dropdown-item">Μαζική Καταχώριση</a>
                            <!-- <a href="element.html" class="dropdown-item">Other Elements</a> -->
                        </div>
                    </div>
                    

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-cubes me-2"></i>Εμφάνιση Υλικών</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="active_product_view.php" class="dropdown-item">Ενεργά Υλικά</a>
                            <a href="deleted_product_view.php" class="dropdown-item">Διεγραμμένα Υλικά</a>
                            <!-- <a href="element.html" class="dropdown-item">Other Elements</a> -->
                        </div>
                    </div>
                    <a href="xreosiproduct.php" class="nav-item nav-link"><i class="fa fa-exchange-alt me-2"></i>Χρέωση Υλικών</a>
                    <a href="prosproothisi.php" class="nav-item nav-link"><i <?php if (existsdata('ekkremeis_xreoseis', $dbcon, $_SESSION['id_monada'] , 'out')){ print " style='background-color: red' ";}?> class="fa fa-angle-double-up me-2"></i>Προς Προώθηση</a>
                    <a href="prosparalavi.php" class="nav-item nav-link"><i <?php if (existsdata('ekkremeis_xreoseis', $dbcon, $_SESSION['id_monada'] , 'in')){ print " style='background-color: red' ";}?> class="fa fa-angle-double-down me-2"></i>Προς Παραλαβή</a>
                    <?php } //ΔΕΝ ΕΧΕΙ ΠΡΟΣΒΑΣΗ Ο ADMIN ΤΩΝ ΧΡΗΣΤΩΝ ?>
                    <a href="logout.php" class="nav-item nav-link"><i class="fa fa-sign-out-alt me-2"></i>Αποσύνδεση</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-folder-open"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <!-- <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form> -->
                

                <div class="navbar-nav align-items-center ms-auto">
                    <?php if ($_SESSION['id_rolos'] == 1 || $_SESSION['id_rolos'] == 0) { ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-layer-group me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Διαχείριση Ενοτήτων</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="manage_monada.php" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <!-- <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;"> -->
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Μονάδες</h6>
                                        <!-- <small>15 minutes ago</small> -->
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="manage_katigoria.php" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Κατηγορίες</h6>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="manage_kataskeuastis.php" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Κατασκευαστές</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php }//ΕΑΝ ΕΙΝΑΙ ΕΝΑΣ ΑΠΟ ΤΟΥΣ ΔΥΟ ADMINS ?>
                    <?php if ($_SESSION['id_rolos'] == 0) { ?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i <?php if (existsdata('users', $dbcon)){ print " style='background-color: red' ";}?> class="fa fa-users me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Χρήστες</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="pending_users.php" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Αποδοχή νέων αιτημάτων</h6>
                                <!-- <small>15 minutes ago</small> -->
                            </a>
                            <hr class="dropdown-divider">
                            <a href="manage_users.php" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Διαχείριση Χρηστών</h6>
                            </a>
                        </div>
                    </div>
                    <?php }//ΕΑΝ ΕΙΝΑΙ Ο ADMIN ΤΩΝ ΧΡΗΣΤΩΝ ?>          
                    <div class="navbar-nav w-100">
                    <?php if ($_SESSION['id_rolos'] == 2){ ?>
                        <a href="manage_tmimata.php" class="nav-item nav-link"><i class="fa fa-home me-lg-2"></i>Διαχείριση Τμημάτων</a>

                    <?php } // Αν είναι Γενικός Διαχειριστής?>
                        <?php if (is_loggedin()) {
                            print "<a href='change_password.php' class='nav-item nav-link'><i class='fa fa-key me-lg-2'></i>Αλλαγή Κωδικού</a>";
                        } ?>
                        <a href="helpdesk.php" class="nav-item nav-link"><i class="fa fa-info me-2"></i>Υποστήριξη</a>
                        <a href="logout.php" class="nav-item nav-link"><i class="fa fa-sign-out-alt me-2"></i>Αποσύνδεση</a>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->


        <?php
        } //ΕΑΝ ΕΙΝΑΙ ΣΥΝΔΕΔΕΜΕΝΟΣ ΧΡΗΣΗΣ, ΑΛΛΙΩΣ-------------------------------------------ΓΙΑ LOGIN Ή REGISTER---------------------------------
        else {
        ?>

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-folder-open me-2"></i>ΔΙΑΧΕΙΡΙΣΗ</h3>
                </a>
                <div class="navbar-nav w-100">
                    <a href="register.php" class="nav-item nav-link"><i class="fa fa-user-plus me-2"></i>Εγγραφή</a>
                    <a href="login.php" class="nav-item nav-link"><i class="fa fa-sign-in-alt me-2"></i>Σύνδεση</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-folder-open"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    
                <div class="navbar-nav w-100">
                    <a href="helpdesk.php" class="nav-item nav-link"><i class="fa fa-info me-2"></i>Υποστήριξη</a>
                    <a href="register.php" class="nav-item nav-link"><i class="fa fa-user-plus me-2"></i>Εγγραφή</a>
                    <a href="login.php" class="nav-item nav-link"><i class="fa fa-sign-in-alt me-2"></i>Σύνδεση</a>
                </div>
                </div>
            </nav>
            <!-- Navbar End -->



        <?php
        }
        ?>
