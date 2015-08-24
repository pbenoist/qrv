<?php
include('connect.php');
include('functions.php');

/*
@ini_set('display_errors', 'on');
  $jsn = '{ 
    "codepin"               : "VH4GM39",
    "nuserie"               : "2", 
    "email_proprio"         : "pbenoist@emergensoft.fr", 
    "nom_proprio"           : "BENOIST",
    "prenom_proprio"        : "Philippe",
    "password_proprio"      : "sansan",
    "tel_proprio"           : "05 56 89 42 62",
    "nom_animal"            : "GAYOUNETTE",
    "code_postal"           : "33000",
    "espece"                : "0",
    "date_naissance"        : "01-02-2012",
    "race"                  : "indéterminée"
     }' ;
  $param = $jsn;
*/

    $param = file_get_contents("php://input");

//myTrace($DB, $param);

    $p = json_decode($param);

    if ($p->password_proprio == '')
        $p->password_proprio = "vethica";

    $v0 = $DB->quote($p->codepin);
    $v1 = $DB->quote($p->nuserie);
    $v2 = $DB->quote($p->email_proprio);
    $v3 = $DB->quote($p->nom_proprio);
    $v4 = $DB->quote($p->prenom_proprio);
    $v5 = $DB->quote($p->password_proprio);
    $v6 = $DB->quote($p->tel_proprio);
    $v7 = $DB->quote($p->nom_animal);
    $v8 = $DB->quote($p->code_postal);
    $v9 = $p->espece;
    $v10 = $DB->quote($p->date_naissance);
    $v11 = $DB->quote($p->race);

    //
    // ==============
    // Le QRCode existe avec le même N° ISO
    // => L'utilisateur est en train de créer son compte sur 2 browsers différents
    // => On ignore !
    //

    if (ISOExist($DB, $v1))
    {
        if (codePinExist($DB, $v0))
        {
            $ret = '{"ret" : "Erreur"}';
            echo($ret);
            return;
        }
    }
    // ==============

    // L'email DOIT être renseigné. Normalement les browsers font le controle mais si ce controle n'a pas fonctionné
    // on ne crée pas l'enregistrement...
    if ($p->email_proprio == '')
    {
        $ret = '{"ret" : "Erreur"}';
        echo($ret);
        return;
    }
    // ==============
    //

    $info = "";
    if (ISOExist($DB, $v1))
    {
        if ($p->nuserie != "")
        {
            $info = 'Attention. Le N° de série '.$p->nuserie. ' a déjà été affecté à un autre animal. <br>
            Véthica est informé de cette erreur et vous informera des corrections effecutées...<br>';
            $v1 = "''";

            $to = 'qrv@vethica.com,pbenoist@emergensoft.fr';
            $subject = 'Anomalie sur inscription qrv.fr';
            $message = $p->codepin. ' Le N° de série '. $p->nuserie .' est déjà attribué<br>';
            $message .= 'Contactez '. $p->nom_proprio;
            mailWithHeader($to, $subject, $message);

        }
    }

    if ($v9 == '')
        $v9 = 0;

    if (codePinExist($DB, $v0))
    {
        $info = $p->codepin. ' existe déjà. Création impossible. Contacter : '. $p->nom_proprio ;
        $to = 'qrv@vethica.com,pbenoist@emergensoft.fr';
        $subject = 'Codepin '.$p->codepin. ' existant';
        $message = $info;
        mailWithHeader($to, $subject, $message);
        $ret = '{"ret" : "Erreur"}';
        echo($ret);
        return;
    }
    
//myTrace($DB, 'avant insert')    ;

    $sql = 'insert into em_animal (codepin,     nuserie, email_proprio, nom_proprio, prenom_proprio, password_proprio, tel_proprio, nom_animal ,    uid  , code_postal, espece, date_naissance, race ) 
                        values ( ' .$v0. ','     .$v1. ','   .$v2. ','      .$v3. ','       .$v4. ','      .$v5. ','        .$v6. ','    .$v7. ' , UUID() , ' .$v8. ' ,' .$v9. ',' .$v10. ',' .$v11. ' )';
    $res = $DB->exec($sql);
    if (!$res)
    {
        // Insert inpossible...
        $ret = '{"ret" : "Erreur"}';
        echo($ret);
        return;
    }
    $w_id = $DB->lastInsertId();

//myTrace($DB, 'id animal : '.$w_id)    ;

    $b = isLoterie ($DB, $v0);
    if ($b)
    {
//myTrace($DB, 'loterie')    ;
      // on change l'état et le n° d'image ET on envoie pas le mail de confirmation
      $sql = 'update em_animal set etat = 1, cpt_modif_img=1 where codepin = '.$v0;
      $res = $DB->exec($sql);
    }
    else
    {
//myTrace($DB, 'envoi mail')    ;
      sendInscriptionMail($DB, $HTTP_ADR, $w_id, $info);
    } 

    $ret = '{"ret": "ok" , "id_animal" : "' . $w_id. '"}';
    echo($ret);
?>

