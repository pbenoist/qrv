<?php

include('connect.php');


/*$search = $_GET['search'];
$search .= '%';
$sea = $DB->quote($search);
$deb = $_GET['deb'];
$size = $_GET['size'];
*/

$param  = file_get_contents("php://input");
$p1     = json_decode($param);

// type d'intervale
$type   = $p1->type;
$sql = "";

if ($type == 0)
{
	$sql = "SELECT FROM_DAYS(TO_DAYS(datCreat) -MOD (TO_DAYS(datCreat) -2, 7)) AS ref, 
	count(*) AS total
	  FROM em_animal GROUP BY FROM_DAYS(TO_DAYS(datCreat) - MOD(TO_DAYS(datCreat) -2, 7))
 		ORDER BY FROM_DAYS(TO_DAYS(datCreat) - MOD(TO_DAYS(datCreat) -2, 7)) ";
}

if ($type == 1)
{
	$sql = "SELECT year(datCreat) as aa, month(datCreat) as mm, concat( year(datCreat), '-', month(datCreat) ) as ref,
	 count(*) AS total
	  FROM em_animal GROUP BY aa, mm, ref
 		ORDER BY aa, mm, ref";
}

if ($type == 2)
{
	$sql = "SELECT year(datCreat) as ref,
	 count(*) AS total
	  FROM em_animal GROUP BY ref 
 		ORDER BY ref";
}

$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsn = json_encode($arr); 
    print_r($jsn);
}

?>
