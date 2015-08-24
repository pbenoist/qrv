<?php
include('connect.php');
include('functions.php');


/*@ini_set('display_errors', 'on');
  $jsn = '{ 
    "p1"               : "123", 
    "p2"               : "11111"
     }' ;
  $param = $jsn;
*/

$param = file_get_contents("php://input");


$p = json_decode($param);
$v0 = $DB->quote($p->p1);
$v1 = $DB->quote($p->p2);

$mess0 = "N° Serie : ". $p->p1;
$mess1 = "Code PIN : ". $p->p2;

$where = ' nuserie = '. $v0;
$sql = 'select nuserie, codepin from em_iso_pin where ' .$where ;
$res = $DB->query($sql);
if ($res)
{   
    $arr = $res->fetch(PDO::FETCH_ASSOC);
    if (!$arr)
    { 	
		// si rien => on verifie codepin
		$where = ' codepin = '. $v1;
		$sql = 'select nuserie, codepin from em_iso_pin where ' .$where ;
		$res2 = $DB->query($sql);
		if ($res2)
		{   
		    $arr2 = $res2->fetch(PDO::FETCH_ASSOC);
    		if (!$arr2)
		    { 	
				// si rien => on peut créer
				$sql = "insert into em_iso_pin (nuserie, codepin, indice) values (".$v0.",".$v1.",999)";
			    $res = $DB->exec($sql);
			    $ret = '{"ret": 0}';
    			print_r($ret);
    			return;
		    }
		    else
		    {
				// si existe => le nuserie est forcément faux
		    	$mess = $mess1. " déjà associé au n° serie ". $arr2['nuserie'];
		        print_r('{"ret" : 10, "mess" : "'.$mess. '" }');
        		return;
		    }
		}
	}
	// Assocation existante
	// si existe => on verifie que le codepin est le bon 
	//	oui => message "Association déjà effectuée"
	//	non => message "nuserie x associé au codepin y"
	else
	{
		if ($arr['codepin'] == $p->p2) 
		{
		    $mess = "Association déjà effectuée";
		    print_r('{"ret" : 11, "mess" : "'.$mess. '" }');
        	return;
		}
		else
		{
			$mess = $mess0. " déjà associé au n° serie ". $arr['codepin'];
		    print_r('{"ret" : 12, "mess" : "'.$mess. '" }');
        	return;
		}

	}
}




?>

