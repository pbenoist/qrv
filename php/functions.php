<?php

function isLoterie($DB, $val)
{
    $sql = 'select loterie from em_pin_free where codepin = '.$val ;

//myTrace($DB, $sql);

    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
        {
            if ($arr['loterie'] == 1)
                return true;
            else
                return false;
        }
    }
    return false;

}

function ISOExist($DB, $val)
{

    $sql = 'select id_animal from em_animal where nuserie = '.$val ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            return true;
    }
    return false;
}

function myTrace($DB, $val)
{
//    echo "in mytrace";
    $tr = $DB->quote($val);
    $sql = 'insert into em_trace (info) values ('. $tr.')';
    $DB->exec($sql);
//    echo "out mytrace";
}


function codePinExist($DB, $val)
{

    $sql = 'select id_animal from em_animal where codepin = '.$val ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            return true;
    }
    return false;
}

function mailWithHeader($to, $subject, $message)
{
     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
    $headers  = 'From: qrv@vethica.com' . "\r\n";
    $headers .= 'Reply-To: qrv@vethica.com' . "\r\n";
    $headers .= 'Return-Path: qrv@vethica.com' ."\r\n";     
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";

    mail($to, $subject, $message, $headers);
}

function em_getBrowser($userAgent)
{
    $browserArray = array(
            'Chrome' => 'Chrome/',
            'Safari' => 'Safari',
            'Safari iPad' => 'iPad',
            'Firefox' => 'Firefox/',
            'Opera' => 'Opera',
            'IE 10' => 'MSIE 10',
            'IE 9' => 'MSIE 9',
            'IE 8' => 'MSIE 8',
            'IE 7' => 'MSIE 7',
            'IE 6' => 'MSIE 6'
    );
    foreach ($browserArray as $k => $value)
        if (strstr($userAgent, $value))
        {
            return $k;
        }
    return "";
}


function historique($DB, $val)
{
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

    $br = em_getBrowser($userAgent);
    $p1 = $DB->quote($br);

    $adr_ip = $_SERVER['REMOTE_ADDR'];
    $p2 = $DB->quote($adr_ip);

    $sql = 'insert into em_historique (codepin, browser, address_ip) values (' .$val. ',' .$p1. ',' .$p2. ')';
//    $sql = 'insert into em_historique (browser, address_ip) values (' .$p1. ',' .$p2. ')';
    $res = $DB->exec($sql);

}


function em_updateISO_PIN($DB, $nuserie, $codepin)
{
    $p1 = $DB->quote($nuserie);
    $p2 = $DB->quote($codepin);

    $sql = 'insert ignore into em_iso_pin (nuserie, codepin) values (' .$p1. ',' .$p2. ')';
/*
    echo ($sql);
    echo ('<br>');
*/
    $res = $DB->exec($sql);
}

function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

function transformImage($src, $image, $dest, $newname, $new_larg, $new_haut)
{
    $imageName = $image;
    $explodename = explode('.', $imageName);   

    $cc = count($explodename);
    $extName = "";
    if ($cc>0)
        $extName = $explodename[$cc-1];

    $extName = strtolower($extName);
    if ($extName == 'jpg' || $extName == 'jpeg')
    {
        $imageName = $src.'/'.$image;
        $imageNewName = $dest. '/' .$newname;

        $imagesize = getimagesize($imageName);
        $src_larg = $imagesize[0];
        $src_haut = $imagesize[1];
       
        $curImage = imagecreatefromjpeg($imageName);

        $destWidth    = $new_larg;
        $destHeight   = $new_haut;
        $sourceWidth  = $src_larg;
        $sourceHeight = $src_haut;
        
        $widthDiff  = $destWidth / $sourceWidth;
        $heightDiff = $destHeight / $sourceHeight;

        if ($widthDiff > 1 AND $heightDiff > 1)
        {
            $nextWidth = $sourceWidth;
            $nextHeight = $sourceHeight;
        }
        else
        {
            if ($widthDiff > $heightDiff)
            {
                    $nextHeight = $destHeight;
                    $nextWidth = round(($sourceWidth * $nextHeight) / $sourceHeight);
            }
            else
            {
                $nextWidth = $destWidth;
                $nextHeight = round($sourceHeight * $destWidth / $sourceWidth);
            }
        }

        $destImage = imagecreatetruecolor($destWidth, $destHeight);
        $white = imagecolorallocate($destImage, 255, 255, 255);
        imagefilledrectangle($destImage, 0, 0, $destWidth, $destHeight, $white);
        imagecopyresampled($destImage, $curImage, (int)(($destWidth - $nextWidth) / 2), (int)(($destHeight - $nextHeight) / 2), 0, 0, $nextWidth, $nextHeight, $sourceWidth, $sourceHeight);

/*        
        $couleur_text = imagecolorallocatealpha($destImage,  $r, $g, $b, $tr);
        // Retrieve bounding box:
        $type_space = imagettfbbox($font_size, 45, $font, $filigrane);
        // Determine image width and height, 10 pixels are added for 5 pixels padding:
        $filigrane_width = abs($type_space[4] - $type_space[0]) + 10;
        $filigrane_height = abs($type_space[5] - $type_space[1]) + 10;
        $l = $destWidth/2 - $filigrane_width/2 ;
        $h = $destHeight/2 + $filigrane_height/2 - $font_size;
        imagettftext($destImage, $font_size, 45, $l, $h, $couleur_text, $font, $filigrane);
*/        
        imagedestroy($curImage);
        imagejpeg($destImage , $imageNewName, 100);
      
    }
    else
    {
        return $image. 'image en jpg SVP';
    }
    return $imageNewName;
}

function sendInscriptionMail($DB, $httpadr, $w_id, $info)
{

//    echo ('dans sendInscriptionMail<br>');

    // recup UID
  $sql = 'select * from em_animal where id_animal = '.$w_id;
  $res = $DB->query($sql);
  $uid = "";
  if ($res)
  {
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    
    //
    $valide = $httpadr. '/php/valideInscription.php?uid='. $arr['UID'];
    $to  = $arr['email_proprio'];
    $subject = 'Création compte Animal ID';

    $info_password = "";
    if ($arr['password_proprio'] == 'vethica')
    {
        $info_password = "<p><em>Vous avez choisi de conserver le mot de passe par défaut : <strong>vethica</strong><br>
        Pour des raisons de sécurité, nous vous recommandons de modifier ce mot de passe dès que possible via
         la mise à jour de la fiche de votre animal.<br>Il vous suffira de cliquer sur le bouton [Changer mon mot de passe]<em><p>";
    }

     // message
     $message = '
     <html>
      <body>
       <p>Bienvenue</p>
       <p>Voici le récapitulatif de vos coordonnées</p>
       <table>
        <tr>
         <td>N° QR Code</td><td><strong>'. $arr['codepin']. '</strong></td>
        </tr>
        <tr>
         <td>N° ISO</td><td><strong>'. $arr['nuserie']. '</strong></td>
        </tr>
        <tr>
         <td>Nom</td><td>'. $arr['nom_proprio']. '</td>
        </tr>
        <tr>
         <td>Prénom</td><td>'. $arr['prenom_proprio']. '</td>
        </tr>
        <tr>
         <td>Téléphone</td><td>'. $arr['tel_proprio']. '</td>
        </tr>
        <tr>
         <td>Nom animal</td><td>'. $arr['nom_animal']. '</td>
        </tr>
        <tr>
         <td>Mot de passe</td><td><strong>'. $arr['password_proprio']. '</strong></td>
        </tr>
       </table>
       <p><em>Ce mot de passe vous permettra d\'accèder à votre compte Animal ID afin de mettre à jour vos informations, 
       mettre une photo de votre animal...</em></p>
       <font color=blue><p> '. $info_password. ' </p></font>

       <font color=red><p> '. $info. ' </p></font>

       <p><strong>Pour activer votre compte cliquez sur le lien suivant : <a href="'.$valide.'">'.$valide.'</a></strong></p>

       <p><em>Suivant votre configuration, il est possible que le lien ci dessus ne marche pas.<br>
Dans ce cas, copier/coller manuellement cette adresse dans la barre d\'adresse de votre navigateur 
</em></p>

      </body>
     </html>
     ';

    mailWithHeader($to, $subject, $message);
  }

}

// L'image a été modifiée => On enregistre un compteur et on renomme l'image
function em_update_image_count($DB, $codepin)
{
    $v0 = $DB->quote($codepin);

    $sql = "update em_animal set cpt_modif_img = cpt_modif_img+1, keepLogin=1, rotate_image=0 where codepin = ".$v0;
    $res = $DB->exec($sql);
    // Recup nouvelle valeur
    $sql = "select cpt_modif_img from em_animal where codepin = ".$v0;
    // On renomme l'image
    $res = $DB->query($sql);
    $cc  = 0;
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            $cc = $arr['cpt_modif_img'];
    }

    $file_from = '../img/' . $codepin. '.jpg';
    $file_to   = '../img/' . $codepin. '_' .$cc. '.jpg';
    rename($file_from, $file_to);

    // On efface le fichier precedent...
    $cc_old = $cc-1;
    $file_old   = '../img/' . $codepin. '_' .$cc_old. '.jpg';
    unlink ($file_old);

    // On efface le fichier initial...
    $file_old   = '../img/user' . $codepin. '.jpg';
    unlink ($file_old);

}

function getID($DB, $cod)
{
    $sql = 'select id_animal from em_animal  where codepin = '.$cod;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if (!$arr)
            return 0;
        return $arr['id_animal'];
    }
}


/*
 * insert blob into the files table
 * @param string $filePath
 * @param string $mime mimetype
 */
function insertBlob($DB, $id, $filePath, $mime)
{
    $blob = fopen($filePath,'rb');
 
    $sql = "INSERT INTO em_images (id_animal, mime,data) VALUES(:id, :mime,:data)";
    $stmt = $DB->prepare($sql);
 
    $stmt->bindParam(':id',$id);
    $stmt->bindParam(':mime',$mime);
    $stmt->bindParam(':data',$blob,PDO::PARAM_LOB);
    return $stmt->execute();
}

/*
 * update the files table with the new blob from the file specified
 * by the filepath
 * @param int $id
 * @param string $filePath
 * @param string $mime
 * @return boolean
 */
function updateBlob($DB, $id,$filePath,$mime)
{
 
    $blob = fopen($filePath,'rb');
 
    $sql = "UPDATE em_images SET mime = :mime, data = :data WHERE id_animal = :id";
    $stmt = $DB->prepare($sql);
 
    $stmt->bindParam(':mime',$mime);
    $stmt->bindParam(':data',$blob,PDO::PARAM_LOB);
    $stmt->bindParam(':id',$id);
    return $stmt->execute();
}

/*
 * select data from the the files
 * @param int $id
 * @return array contains mime type and BLOB data
 */
function selectBlob($DB, $id)
{
    $sql = "SELECT mime, data FROM em_images WHERE id_animal = ".$id ;
    $stmt = $DB->prepare($sql);
    $stmt->execute();
    $stmt->bindColumn(1, $mime);
    $stmt->bindColumn(2, $data, PDO::PARAM_LOB);
    $ret = $stmt->fetch(PDO::FETCH_BOUND);
    return array("mime" => $mime, "data" => $data);
 
}

?>

