<?php

include('connect.php');

/* 
Error 100 : Not Found

*/

/*
$cod = $_GET['cod'];
$password = $_GET['pwd'];
$cod        = $DB->quote($cod);
$password   = $DB->quote($password);
*/


$param = file_get_contents("php://input");
$p = json_decode($param);
$cod        = $DB->quote($p->codepin);
$password   = $DB->quote($p->password);

$sql = 'select id_animal, 0 as error from em_animal 
 where codepin = '.$cod .' and password_proprio = '. $password;

$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    if (!$arr)
    {
        print_r('{"error" : 100 , "err_desc" : "Mot de passe incorrect"}');
    }
    else
    {
        $jsn = json_encode($arr); 
        print_r($jsn);
    }
}

?>

