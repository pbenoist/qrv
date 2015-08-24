<?php
include('connect.php');

$p1  = $_GET['uid'];
$p1x = $DB->quote($p1);

$sql = 'update em_animal set etat = 1, keepLogin = 1  where uid = '.$p1x;
$res = $DB->exec($sql);

$sql = 'select codepin from em_animal where uid = '.$p1x;
$res = $DB->query($sql);
$codepin = "";
if ($res)
{   
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    if ($arr)
    	$codepin = $arr['codepin'];
}

$ret = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Animal ID - Powered by Vethica</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">       
    <link rel="stylesheet" href="../css/validation.css">       
    <meta name="viewport" content="initial-scale=1.0">
</head>
 
<body ng-app="mainApp">

<div class="container">

<h3 class="text-center">Félicitations. Votre compte est validé</h3>
<hr>
<div class="text-center">Cliquez <a class="btn btn-sm btn-primary" href="../'.$codepin.'">ICI</a>pour accèder directement à votre fiche.<br>Ci-dessous quelques conseils pour bien débuter...</div>
<hr>

<div class="row text-center">
    <img class="info_img" src="../help/qrv_002.jpg"/>
    <div class="well info_comment_2">En cliquant sur cette option, vous pourrez modifier vos coordonnées...</div>
</div>

<div class="row text-center">
    <img class="info_img" src="../help/qrv_003.jpg"/>
    <div class="well info_comment_2">En cliquant sur cette option, vous pourrez mettre la photo de votre animal...</div>
</div>

<br>
<br>

<div class="col-md-4 col-md-offset-3">
    <em>Nous vous conseillons de faire un portrait assez proche de votre animal 
afin que celui-ci soit bien reconnaissable. 
Vous pourrez modifier cette photo à tout moment, lorsque qu\'il sera adulte, par exemple 
    </em>
</div>
<br>

<div class="col-md-12">

<div class="row text-center">
    <img class="info_img" src="../help/qrv_gaia.jpg"/>
    <div class="well info_comment_gaia">Choisissez une photo présente sur votre ordinateur 
ou sur votre téléphone portable...<br><br>
<em>N\'oubliez pas de mettre à jour !</em>
    </div>
</div>

<hr>
<div class="row text-center">
    <img class="info_img" src="../help/qrv_005.jpg"/>
    <div class="well info_comment_3">Si votre photo n\'est pas correctement orientée, vous pourrez la faire pivoter ici...</div>
</div>

<hr>
<div class="row text-center">
    <img class="info_img" src="../help/qrv_004.jpg"/>
    <div class="well info_comment_4">Si vous avez des questions, contactez nous à l\'aide de ce bouton</div>
</div>

<hr>
<br>
<div class="text-center">Cliquez <a class="btn btn-sm btn-primary" href="../'.$codepin.'">ICI</a>pour accèder à votre fiche</div>

</div>
</div>

</body>
</html>
';

echo($ret);

?>

