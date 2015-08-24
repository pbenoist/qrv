<?php
include('connect.php');
include('functions.php');

$param = file_get_contents("php://input");
$p = json_decode($param);
$v0 = $DB->quote($p->codepin);

/*
    ini_set('display_errors', 'off');
    $v0 = $DB->quote('VRD7V86');
*/
    
$sql = 'select email_proprio, password_proprio, nom_animal from em_animal where codepin = ' .$v0;    
    $message = '';
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        // message
        $message = '
<html>
    <body>
    <p>Bonjour,</p>
    <p>Merci de votre inscription sur qrv.fr</p>
    <p>Votre mot de passe pour vous connecter sur la fiche de '.$arr['nom_animal']. ' est : <strong>'. $arr['password_proprio'].'</strong> </p>
    <p>Cordialement<br>L\'équipe QRV pour Vethica</p>
    </body>
</html>
';

        $to         = $arr['email_proprio'];
        $subject    = 'Votre demande de mot de passe sur QRV.FR';

        mailWithHeader($to, $subject, $message);
    }
    else
    {
        echo 'erreur';
    }

?>

