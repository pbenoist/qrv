<?php

include('connect.php');

$param = file_get_contents("php://input");
$p1 	= json_decode($param);
$search = $p1->search;
$sea 	= $DB->quote($search. '%');
$jsn 	= "";

$sql = 'select count(*) as cc from em_animal ' ;

if ($search != "") {
	$sql .= ' where (
	   nom_proprio like '.$sea. ' 
	or prenom_proprio like '.$sea. '
	or codepin like '.$sea. '
	or nuserie like '.$sea. ')';
}

$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    $jsn = json_encode($arr); 
    print_r($jsn);
}
else
{
	$jsn = '{ "cc" : "Erreur"}';
	print_r($jsn);
}

?>

