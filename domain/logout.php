<?php
//ΣΕΛΙΔΑ ΑΠΟΣΥΝΔΕΣΗΣ ΧΡΗΣΤΗ
$title = 'Αποσύνδεση';
require('../templates/header.inc.php');
check_session();
//$username = $_SESSION['username'];
$_SESSION = [];
session_destroy();

ob_end_clean();
header("Location: index.php\n");
exit();

require('../templates/footer.inc.php');
?>