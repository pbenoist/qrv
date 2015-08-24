<?php

include('../config/define.php');

function trace($file, $var)
{
    ob_start();
    echo $var. "\n";
    $page = ob_get_contents();
    ob_end_clean();
    $fw = fopen($file, "a");
    fputs($fw,$page, strlen($page));
    fclose($fw);
}


$DB = new PDO($DSN, $USER, $PASSWORD);
$DB->exec("set names utf8");


?>
