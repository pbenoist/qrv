<?php

include('connect.php');

ini_set('display_errors', 'on');

/*
$p1 = $_GET['p1'];
$p2 = $_GET['p2'];
$p3 = $_GET['p3'];
$p4 = $_GET['p4'];
$p5 = $_GET['p5'];
$p6 = $_GET['p6'];
$p2 	= $DB->quote($p2);
$p3 	= $DB->quote($p3);
$p6 	= $DB->quote($p6);
*/


$param = file_get_contents("php://input");
$jsn = json_decode($param);

$p1 	= $jsn->p1;
$p2 	= $DB->quote($jsn->p2);
$p3 	= $DB->quote($jsn->p3);
$p4 	= $jsn->p4;
$p5 	= $jsn->p5;
$p6 	= $DB->quote($jsn->p6);

	
$sql = 'update em_animal set nuserie = '.$p2.',email_proprio='.$p3.',rotate_image='.$p4.',etat='.$p5.',password_proprio='.$p6.' where id_animal = ' .$p1;
$res = $DB->exec($sql);

echo ($sql);

?>
