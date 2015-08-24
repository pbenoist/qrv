<?php

include('connect.php');

/*
$p1 = $_GET['val'];
$val = $DB->quote($p1);
*/

$param = file_get_contents("php://input");
$p1 = json_decode($param);
$id = $DB->quote($p1->id);

$sql = 'delete from em_animal where id_animal = '.$id;
$res = $DB->exec($sql);

?>
