<?php

include('connect.php');

/* 
Error 100 : Not Found
*/

/*
$cod = $_GET['cod'];
$cod        = $DB->quote($cod);
*/

$param = file_get_contents("php://input");
$p = json_decode($param);
$cod        = $DB->quote($p->codepin);

$sql = 'select nuserie, 0 as ret from em_iso_pin where codepin = '.$cod;
$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    if (!$arr)
    {
        print_r('{"ret" : 100 , "err_desc" : "N° Iso Inconnu"}');
    }
    else
    {
        $jsn = json_encode($arr); 
        print_r($jsn);
    }
}

?>

