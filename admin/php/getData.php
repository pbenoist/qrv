<?php

include('connect.php');


/*$search = $_GET['search'];
$search .= '%';
$sea = $DB->quote($search);
$deb = $_GET['deb'];
$size = $_GET['size'];
*/

$param = file_get_contents("php://input");
$p1 = json_decode($param);
$search = $p1->search;
$deb 	= $p1->deb;
$size 	= $p1->size;

$sea 		 = $DB->quote($search.'%');
$sea_inverse = $DB->quote('%'.$search);

$sql = 'select id_animal, date_format( datCreat, "%d-%m-%Y" ) as date_create, upper(codepin) as codepin , nuserie, 
  nom_proprio, prenom_proprio, tel_proprio, email_proprio, code_postal, cpt_modif_img,
  concat( upper(codepin), "_", cpt_modif_img ) as image_name ,
  etat, password_proprio, rotate_image from em_animal ';

if ($search != "") {
	$sql .= ' where (
	   nom_proprio like '.$sea. ' 
	or prenom_proprio like '.$sea. '
	or codepin like '.$sea. '
	or nuserie like '.$sea_inverse. ')';
}

$sql .= ' order by id_animal desc limit ' .$deb. ',' .$size ;

//echo ($sql);

$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetchAll(PDO::FETCH_ASSOC);

    $jsn = json_encode($arr); 
    print_r($jsn);
}

?>
