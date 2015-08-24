<?php
include('connect.php');


/*
$p1 = $_GET['val'];
$val = $DB->quote($p1);
*/

$param = file_get_contents("php://input");
$p1 = json_decode($param);
$val = $DB->quote($p1->val);

$pin = false;
$iso = false;
$where = "";

// ==============================
// Attention. Longueur + 2 avec les quotes ...
if (strlen($val) == 9)
{
    $pin = true;   
    $where = 'codepin = ' .$val;
}

if (strlen($val) == 17)
{
    $iso = true;
    $where = 'nuserie = ' .$val;
}

if ($iso == false && $pin == false)
{
    print_r('{"id_animal" : 0, "error" : 200, "err_desc" : "Code erroné" }');
    return;
}

if ($pin)
{
    $b = isCodepinValide($DB, $val);
    if (!$b)
    {
        print_r('{"id_animal" : 0, "error" : 500, "err_desc" : "Code PIN inconnu" }');
        return;
    }
}


$sql = 'select id_animal, UPPER(codepin) as codepin, nuserie, nom_animal, email_proprio, nom_proprio, 
 prenom_proprio, tel_proprio, etat, cpt_modif_img, 0 as error,
 code_postal, espece, date_naissance, race, rotate_image, keepLogin
 from em_animal where ' .$where ;


$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    if (!$arr)
    {
        if ($pin)
        {
            // On a donné un code pin inconnu => On va passer en création, 
            // mais on cherche si code ISO existant dans la base (ou un code pin)
            $ret = searchIso($DB, $val);
            if (!$ret)
                print_r('{"id_animal" : 0, "error" : 400, "err_desc" : "Code PIN inconnu" }');
            else
                print_r('{"id_animal" : 0, "error" : 300,
                          "err_desc" : "Mode création (à partir Code PIN)",
                          "nuserie" : "' .$ret['nuserie']. '",
                          "codepin" : "' .$ret['codepin']. '"}');
            return;
        }
        if ($iso)
        {
            $ret = searchPin($DB, $val);
            if (!$ret)
                print_r('{"id_animal" : 0, "error" : 400, "err_desc" : "Code ISO inconnu" }');
            else
                print_r('{"id_animal" : 0, "error" : 300,
                          "err_desc" : "Mode création (à partir Code ISO)",
                          "nuserie" : "' .$ret['nuserie']. '",
                          "codepin" : "' .$ret['codepin']. '"}');
            return;
        }

    }
    else
    {
        if ($arr['keepLogin'] == 1)
        {
            // =====================
            $sql = 'update em_animal set keepLogin  = 0 where ' .$where ;
            $res = $DB->exec($sql);
        }
        // Ajoute image_name
        // par default c'est codepin mais si inexistant, ca devient ... empty.jpg
        // traitement image
        // Pour forcer le cache à se refraichir, on change le nom de l'image avec un rang de modif...
        // (à cahque modificiation d'image, on met +1 dans le rang)
        $arr['image_name'] = $arr['codepin'] . "_" .$arr['cpt_modif_img'];
        // 
        $filename = '../img/'. $arr['image_name'] . ".jpg";
        if ($filename == '' || !file_exists ($filename))
            $arr['image_name'] = 'chien-2';

        $jsn = json_encode($arr); 
        print_r($jsn);
    }

}

function searchIso($DB, $val)
{

    $sql = 'select nuserie, codepin from em_iso_pin where codepin = '.$val ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            return $arr;
    }
    return "";

}

function searchPin($DB, $val)
{
    $sql = 'select nuserie, codepin from em_iso_pin where nuserie = '.$val ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            return $arr;
    }
    return "";

}

function isCodepinValide($DB, $val)
{
    // Cherche dans les médailles associées aux n° ISO
    $sql = 'select codepin from em_iso_pin where codepin = '.$val ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            return true;
    }

    // Cherche dans les médailles SANS n° ISO
    $sql = 'select codepin from em_pin_free where codepin = '.$val ;
    $res = $DB->query($sql);
    if ($res)
    {   
        $arr = $res->fetch(PDO::FETCH_ASSOC);
        if ($arr)
            return true;
    }

    return false;

}

?>

