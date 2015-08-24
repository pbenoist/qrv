<?php

require('functions.php');
include('connect.php');

$sql = "select * from em_animal order by datCreat desc ";

$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetchAll(PDO::FETCH_ASSOC);

download_send_headers("qrv_export_" . date("Y-m-d") . ".csv");
echo array2csv($arr);
die();

}

?>