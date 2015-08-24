<?php

require('functions.php');
include('connect.php');

$sql = "select a.codepin, a.email_proprio, a.nom_proprio, a.prenom_proprio, a.tel_proprio, a.code_postal, a.datCreat
 from em_animal a, em_pin_free f where f.loterie = 1 and f.codepin = a.codepin
 order by a.datCreat ";

$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetchAll(PDO::FETCH_ASSOC);
	download_send_headers("qrv_loterie_" . date("Y-m-d") . ".csv");
	echo array2csv($arr);

	die();
}
?>