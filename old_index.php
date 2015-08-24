<?php
$str = $_GET['str'];
$url = "";
if ($str == '')
	$url =  'main.html#/Search';
else
	$url =  'main.html#/Show/' .$str;
header('Location: '.$url);
?>
