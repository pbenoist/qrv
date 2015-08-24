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
    $v1 = $DB->quote($p->nuserie);
    $v2 = $DB->quote($p->nom_proprio);
    $v3 = $DB->quote($p->prenom_proprio);
    $v4 = $DB->quote($p->tel_proprio);
    $v5 = $DB->quote($p->nom_animal);
    $v6 = $DB->quote($p->code_postal);
    $v9 = $p->espece;
    $v10 = $DB->quote($p->date_naissance);
    $v11 = $DB->quote($p->race);
    $v12 = $DB->quote($p->email_proprio);


    $sql = 'update em_animal set nuserie     = '.$v1. '
                            , nom_proprio    = '.$v2. '
                            , prenom_proprio = '.$v3. '
                            , tel_proprio    = '.$v4. '
                            , nom_animal     = '.$v5. '
                            , code_postal    = '.$v6. '
                            , espece         = '.$v9. '
                            , date_naissance = '.$v10. '
                            , race           = '.$v11. '
                            , email_proprio  = '.$v12. '
                            where codepin    = '.$v0;
   
    
    $res = $DB->exec($sql);


    $ret = '{"ret": "ok"}';
    echo($ret);


?>

