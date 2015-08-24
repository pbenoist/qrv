<?php
include('functions.php');
include('connect.php');

/*
@ini_set('display_errors', 'on');
  $jsn = '{ 
    "codepin"               : "VXGU6B3",
    "email_proprio"         : "pbenoist@emergensoft.fr"
     }' ;
  $param = $jsn;
*/

    $param = file_get_contents("php://input");

    $p = json_decode($param);

    $v0 = $DB->quote($p->codepin);
    $v1 = $DB->quote($p->email_proprio);

    $sql = 'update em_animal set email_proprio = '.$v1. ' where codepin = '.$v0;
    $res = $DB->exec($sql);

    $info = "";

    $sql = 'select id_animal from em_animal where codepin = '.$v0 ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
        {
		    sendInscriptionMail($DB, $HTTP_ADR, $arr['id_animal'], $info);
        }
    }

    $ret = '{"ret": "ok"}';
    echo($ret);


?>

