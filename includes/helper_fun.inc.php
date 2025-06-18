<?php

    function check_session()
    {
        if (!is_loggedin()) {
            call_errorpage('Δεν είστε συνδεδεμένος χρήστης!');
        }
    }

    function check_no_session()
    {
        if (is_loggedin()) {
            call_errorpage('Είστε συνδεδεμένος χρήστης!');
        }
    }

    function check_admin_products()
    {
        if ($_SESSION['id_rolos'] != 1) {
            call_errorpage('Σελίδα για διαχειριστή υλικών!');
        }
    }

    function check_no_admin_products()
    {
        if ($_SESSION['id_rolos'] == 1) {
            call_errorpage('Σελίδα μη πρόσβασης για διαχειριστή υλικών!');
        }
    }

    function check_admin_users()
    {
        if ($_SESSION['id_rolos'] != 0) {
            call_errorpage('Σελίδα για διαχειριστή χρηστών!');
        }
    }

    function check_no_admin_users()
    {
        if ($_SESSION['id_rolos'] == 0) {
            call_errorpage('Σελίδα μη πρόσβασης για διαχειριστή χρηστών!');
        }
    }

    function is_loggedin()
    {
        if (isset($_SESSION['username']) && isset($_SESSION['time']) && isset($_SESSION['agent']) && $_SESSION['agent'] == sha1($_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        } 
    }

    function call_errorpage($e)
    {
        header("Location: errorpage.php?e=$e\n"); // Ανακατεύθυνση στη σελίδα λάθους
        exit();
    }

    function print_error_message($error)
    {
        print "<p class='alert alert-danger text-center' role='alert'>$error</p>\n";        
    }

    function print_by_id($table_name, $id, $dbcon)
    {
        try {
            
            $param1 = $table_name."_name";
            $param3 = "id_".$table_name."=".$id;
            $qbyid = "SELECT $param1 FROM $table_name WHERE $param3";
            $stmtbyid = mysqli_prepare($dbcon, $qbyid);  

            mysqli_stmt_execute($stmtbyid);
            mysqli_stmt_store_result($stmtbyid);
            if (mysqli_stmt_num_rows($stmtbyid) == 1) {
                mysqli_stmt_bind_result($stmtbyid, $name);
                mysqli_stmt_fetch($stmtbyid); 
                mysqli_stmt_close($stmtbyid);
                
                
                return "$name";
            } else {return 
                mysqli_stmt_close($stmtbyid);
                
                "error_in_helper_fun-print by id-epistrofi perissoteron";
            }
        } catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }
    }

    function get_monada_by_tmima($id_tmima, $dbcon)
    {
        try{
            $qmonadabytmima = "SELECT id_monada from tmima where id_tmima=?";
            $stmtmonadabytmima = mysqli_prepare($dbcon, $qmonadabytmima);
            mysqli_stmt_bind_param($stmtmonadabytmima, 'i', $id_tmima);
            mysqli_stmt_execute($stmtmonadabytmima);
            mysqli_stmt_store_result($stmtmonadabytmima);
            mysqli_stmt_bind_result($stmtmonadabytmima, $id_monada);
            mysqli_stmt_fetch($stmtmonadabytmima);
            return $id_monada;
            mysqli_stmt_close($stmtmonadabytmima);

        }catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }
    }

    function get_next_barcode($dbcon)
    {
        try{
            $qnextbarcode = "SELECT (max(barcode)+1) from products";
            $stmtnextbarcode = mysqli_prepare($dbcon, $qnextbarcode);
            mysqli_stmt_execute($stmtnextbarcode);
            mysqli_stmt_store_result($stmtnextbarcode);
            mysqli_stmt_bind_result($stmtnextbarcode, $nextbarcode);
            mysqli_stmt_fetch($stmtnextbarcode);
            return $nextbarcode;
            mysqli_stmt_close($stmtnextbarcode);

        }catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }
    }

    function dropdown_menus_by_table($table_name, $dbcon, $id_lastused=NULL, $id_monadafortmima=NULL)
    {
        try{
            $param1 = "id_".$table_name;
            $param2 = $table_name."_name";
            if ($table_name == 'tmima'){
                $qdropdownbyid = "SELECT $param1, $param2 FROM $table_name where id_monada=? ";
                $stmtdropdownbyid = mysqli_prepare($dbcon, $qdropdownbyid);  
                mysqli_stmt_bind_param($stmtdropdownbyid, 'i', $id_monadafortmima);
            } else {
                $qdropdownbyid = "SELECT $param1, $param2 FROM $table_name";
                $stmtdropdownbyid = mysqli_prepare($dbcon, $qdropdownbyid);  
            }
            

            mysqli_stmt_execute($stmtdropdownbyid);
            mysqli_stmt_store_result($stmtdropdownbyid);
            mysqli_stmt_bind_result($stmtdropdownbyid, $idhf, $namehf);
            while (mysqli_stmt_fetch($stmtdropdownbyid)) {
                $idhf =  intval(strip_tags($idhf));
                $namehf = strip_tags($namehf);
                if ($id_lastused == $idhf){
                    print "<option value='$idhf' selected>$namehf</option>";
                } else {
                    print "<option value='$idhf'>$namehf</option>";
                };
            }
        
            mysqli_stmt_close($stmtdropdownbyid);
        }catch (mysqli_sql_exception $e) {
            var_dump($e);
            $errorpage_url = "errorpage.php?e=" . urlencode($e);
            header("Location: $errorpage_url");
            exit();
        }
        
    }

    function existsdata($table_name, $dbcon, $monada_id=null, $status=null)
    {
        $param1 = $table_name;
        if ($param1=='ekkremeis_xreoseis') {
            $param2=$monada_id;
            if ($status=='in') {
                $q1 = "SELECT count(*) from $param1 where id_monada_after=$param2";
            } elseif ($status=='out'){
                $q1 = "SELECT count(*) from $param1 where id_monada_before=$param2";
            }
        } elseif ($param1=='users') {
            $q1 = "SELECT count(*) from $param1 where energos=false";
        }
        $stmt1 = mysqli_prepare($dbcon, $q1);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_store_result($stmt1);
        mysqli_stmt_bind_result($stmt1, $res);
        mysqli_stmt_fetch($stmt1);
        if ($res>=1){
            return true;
        } else {
            return false;
        }
        mysqli_stmt_close($stmt1);
    }


    // function catch_default($e, $dbcon)
    // {
            
            
    //         $errorpage_url = "errorpage.php?e=" . urlencode($e);
    //         try {
    //         mysqli_close($dbcon);
    //         } catch (Exception $e) {
                
    //             $errorpage_url = "errorpage.php?e=" . urlencode($e);
    //             header("Location: $errorpage_url");
    //             exit();
    //         }

    //         header("Location: $errorpage_url");
    //         exit();
        
    // }

?>

<script>
    function showHistory(button, barcode, aa) {
        button.remove();
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var txtHint = "txtHint"+aa;
                document.getElementById(txtHint).innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","getHistory.php?barcode="+barcode,true);
        xmlhttp.send();
    }// ΓΙΑ ΔΥΝΑΜΙΚΟ ΕΡΩΤΗΜΑ ΓΙΑ ΥΠΑΡΞΗ ΙΣΤΟΡΙΚΟΥ (ΣΤΙΣ ΣΕΛΙΔΕΣ ΕΜΦΑΝΙΣΗΣ ΥΛΙΚΩΝ)

    function userReply(id_users, action, aa) {
        var id1 = "reject" + aa;
        var id2 = "accept" + aa;
        button1 = document.getElementById(id1);
        button2 = document.getElementById(id2);
        button1.remove();
        button2.remove();
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var txtHint = "txtHint" + aa;
                document.getElementById(txtHint).innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","noview_replytouser.php?id_users="+id_users+"&action="+action,true);
        xmlhttp.send();
    }// ΓΙΑ ΔΥΝΑΜΙΚΟ ΕΡΩΤΗΜΑ ΓΙΑ ΑΠΟΔΟΧΗ/ΑΠΟΡΡΙΨΗ ΑΙΤΗΜΑΤΟΣ ΔΗΜΙΟΥΡΓΙΑΣ ΧΡΗΣΤΗ

    function downloadTableAsExcel() {
        // Get the table element
        var table = document.getElementById('downloadable');
        // Convert table to a worksheet
        var worksheet = XLSX.utils.table_to_sheet(table);
        // Create a new workbook and append the worksheet
        var workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
        // Convert the workbook to a binary array
        var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' });
        // Create a Blob object from the binary array
        function s2ab(s) {
            var buf = new ArrayBuffer(s.length); // Convert s to arrayBuffer
            var view = new Uint8Array(buf);  // Create uint8array
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }
        var blob = new Blob([s2ab(wbout)], { type: 'application/octet-stream' });
        // Create a link element and trigger a download
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "table_data.xlsx";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }// ΓΙΑ ΚΑΤΕΒΑΣΜΑ ΣΥΓΚΕΚΡΙΜΕΝΟΥ ΠΙΝΑΚΑ ΣΕ EXCEL


    function removeElement(id) {
        // Hide the confirmation dialog
        elem = document.getElementById(id);
        elem.remove();
    }// GENARAL ELEMENT REMOVAL FUNCTION

    function deleteProduct(id, barcode){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET","noview_deleteproduct.php?barcode="+barcode,true);
        xmlhttp.send();
        removeElement(id);
    }// FOR PRODUCT DELETION

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
