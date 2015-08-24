<?php
$str = $_GET['str'];
$url = "";

if ($str == '')
{	
	$url =  'main.html#/Search';
}
else
{
	if ($str == 'admin')
		$url =  'admin/index.html';
	else
		$url =  'main.html#/Show/' .$str;
}
header('Location: '.$url);
?>
