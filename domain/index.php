<?php
require('../templates/header.inc.php');
if (is_loggedin() && $_SESSION['id_rolos'] == 0) {
    ob_end_clean();
    header("Location: control_panel.php\n"); 
    exit();
}else if(is_loggedin()){
    ob_end_clean();
    header("Location: active_product_view.php\n"); 
    exit();
}else{
    ob_end_clean();
    header("Location: login.php\n"); 
    exit();
}
require('../templates/footer.inc.php');
?>