<?php

include('connect.php');
$jsn = "";
$arr1 = array();
$arr2 = array();

$sql = "select count(*) as cc from em_animal where nuserie <> ''" ;
$res = $DB->query($sql);
if ($res)
{   
    $arr1 = $res->fetch(PDO::FETCH_ASSOC);
}

$sql = "select count(*) as cc from em_animal where nuserie = ''" ;
$res = $DB->query($sql);
if ($res)
{   
    $arr2 = $res->fetch(PDO::FETCH_ASSOC);
}
/*
echo ($arr1['cc']);
echo ('<br>');
echo ($arr2['cc']);
echo ('<br>');
*/
$v1 = $arr1['cc'];
$v2 = $arr2['cc'];

$jsn = '{ "cc1" : ' .$v1. ', "cc2" : ' .$v2. '}';
print_r($jsn);

?>

