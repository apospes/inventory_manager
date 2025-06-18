<?php

$dbhost = 'db';
$dbuser = 'user';
$dbpass = 'pass';
$dbname = 'diaxirisi';
try{
    $dbcon = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    mysqli_set_charset($dbcon, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}

if (!$dbcon) {
    ob_end_flush();
    header("Location: server_error_page.php");
    exit();
}


?>