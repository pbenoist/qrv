<?php

include('connect.php');
include('functions.php');



$param = $_POST['param'];

$sFileName = $_FILES['image_file']['name'];
$sFileType = $_FILES['image_file']['type'];
$sFileSize = bytesToSize1024($_FILES['image_file']['size'], 1);


//myTrace($DB, 'upload.php');

// on envoi le fichier sur le site avec le bon nom...
$uploaddir = '../img/';
$sTmpName = $_FILES['image_file']['tmp_name'];

// destination
$sDestFileName = $uploaddir . 'user'. $param. ".jpg";
if (move_uploaded_file($sTmpName, $sDestFileName)) 
{
	$newname = $param. ".jpg";
	$thenewfile = transformImage($uploaddir, $sDestFileName, $uploaddir, $newname, 240, 320);
	$tmp = $uploaddir. $thenewfile;

//myTrace($DB, $param);

	$id = getID($DB, $param);
	if ($id)
	{
//		myTrace($DB, 'avant insertblob');
		insertBlob($DB, $id, $tmp, $sFileType);
//		myTrace($DB, 'après insertblob');
	}
    // on renomme l'image en fonction du compteur de changement d'images dans la table...
    em_update_image_count($DB, $param);

    // on ecrit l'image dans un blob
}
else
{
    echo "Erreur envoi fichier\n";
}

/*
echo <<<EOF
<p>Your file: {$sFileName} has been successfully received.</p>
<p>Type: {$sFileType}</p>
<p>Size: {$sFileSize}</p>
EOF;
*/

