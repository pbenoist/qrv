<?php


/*
ini_set('display_errors', 'on');
*/

ini_set('display_errors', 'off');


$var = $_SERVER['DOCUMENT_ROOT'];

$HTTP_ADR   = '';
$DSN 		= '';
$USER 		= '';
$PASSWORD 	= '';
$SRVNAME    = '';
$DBNAME     = '';

if ($var == 'C:/wamp/www/')
{
	$HTTP_ADR   = 'localhost/qrv';
	$DSN 		= 'mysql:host=localhost;dbname=qrvet';
	$USER 		= 'root';
	$PASSWORD 	= '';
	$SRVNAME    = 'localhost';
	$DBNAME     = 'qrvet';
}
else
{
	$HTTP_ADR   = 'http://qrv.fr';
	$DSN 		= 'mysql:host=jbvethicqrcode.mysql.db;dbname=jbvethicqrcode';
	$USER 		= 'jbvethicqrcode';
	$PASSWORD 	= 'VEuPFRZJegkH';
	$SRVNAME    = 'jbvethicqrcode.mysql.db';
	$DBNAME     = 'jbvethicqrcode';
}

?>
