<?php
include('connect.php');

/*
  $jsn = '{ 
    "codepin"               : "11111",
    "nuserie"               : "25611111", 
    "email_proprio"         : "pbenoist@emergensoft.fr", 
    "nom_proprio"           : "BENOIST",
    "prenom_proprio"        : "Philippe",
    "password_proprio"      : "sansan",
    "tel_proprio"           : "05 56 89 42 62",
    "nom_animal"            : "GAYOUNETTE"
     }' ;
  $param = $jsn;
*/

    $param = file_get_contents("php://input");
    $p = json_decode($param);
    $v0 = $DB->quote($p->codepin);
    $subject = $p->obj;
    $message = $p->message;
    $headers = '';
    $arr = array();

    if (strlen($v0) < 7)
    {
        $headers  = 'From: qrv@vethica.com' . "\r\n";
        $headers .= 'Reply-To: qrv@vethica.com' . "\r\n";
        $headers .= 'Return-Path: qrv@vethica.com' ."\r\n";     
    }
    else
    {
        $sql = 'select email_proprio, nom_proprio, prenom_proprio from em_animal where codepin = ' .$v0;
        $res = $DB->query($sql);
        if ($res)
        {   
            $arr = $res->fetch(PDO::FETCH_ASSOC);
            $headers  = 'From: '. $arr['email_proprio'].        "\r\n";
            $headers .= 'Reply-To: '. $arr['email_proprio'].    "\r\n";
            $headers .= 'Return-Path: '. $arr['email_proprio']. "\r\n";     
        }
    }

    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset="UTF-8"' . "\r\n";

    $to = 'qrv@vethica.com,pbenoist@emergensoft.fr';
    $message .= '<br>';
    $message .= '<br>QRCode : ' . $p->codepin;
    $message .= '<br>Propriètaire : ' . $arr['nom_proprio'];
    mail($to, $subject, $message, $headers);


    $ret = '{"ret": "ok"}';
    echo($ret);


?>

