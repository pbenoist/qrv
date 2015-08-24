<?php

/*
ini_set('display_errors', 'on');
*/

ini_set('display_errors', 'off');


$var = $_SERVER['DOCUMENT_ROOT'];

if ($var == 'C:/wamp/www/')
{
	$HTTP_ADR   = 'localhost/qrv';
	$DSN 		= 'mysql:host=localhost;dbname=qrvet';
	$USER 		= 'root';
	$PASSWORD 	= '';
}
else
{
	$HTTP_ADR   = 'http://qrv.fr';
	$DSN 		= 'mysql:host=jbvethicqrcode.mysql.db;dbname=jbvethicqrcode';
	$USER 		= 'jbvethicqrcode';
	$PASSWORD 	= 'VEuPFRZJegkH';
}

?>
