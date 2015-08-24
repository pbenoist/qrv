<?php

include('connect.php');
include('functions.php');

$param = file_get_contents("php://input");
$p1 = json_decode($param);
$val = $DB->quote($p1->val);

historique($DB, $val);


?>

