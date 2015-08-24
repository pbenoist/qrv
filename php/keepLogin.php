<?php
include('connect.php');

/*
  $jsn = '{ 
    "codepin"               : "11111",
    "nuserie"               : "25611111", 
    "email_proprio"         : "pbenoist@emergensoft.fr", 
    "nom_proprio"           : "BENOIST",
    "prenom_proprio"        : "Philippe",
    "password_proprio"      : "sansan",
    "tel_proprio"           : "05 56 89 42 62",
    "nom_animal"            : "GAYOUNETTE"
     }' ;
  $param = $jsn;
*/

    $param = file_get_contents("php://input");


    $p = json_decode($param);
    $v0 = $DB->quote($p->codepin);
    $v1 = $p->val;

    $sql = 'update em_animal keepLogin  = '.$v1. '
                            where codepin    = '.$v0;
   
    
    $res = $DB->exec($sql);


    $ret = '{"ret": "ok"}';
    echo($ret);


?>

