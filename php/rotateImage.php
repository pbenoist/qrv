<?php
include('connect.php');


/*
  $jsn = '{ 
    "val"               : "azer001",
    "rotate"               : 90 
     }' ;
  $param = $jsn;
*/

    $param = file_get_contents("php://input");

    $p = json_decode($param);
    $v0 = $DB->quote($p->val);
    $v1 = $p->rotate;

    $sql = 'update em_animal set rotate_image    = '.$v1. '
                            where codepin    = '.$v0;
   
    $res = $DB->exec($sql);
    $ret = '{"ret": "ok"}';
    echo($ret);

?>

