<?PHP

include '../config/define.php';

header('Content-Type: text/plain');

$mysqli = new mysqli($SRVNAME, $USER, $PASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
	die("-1");
}

$iso = $_GET["iso"];
$code = $_GET["code"];
$isofix = "";

$LEFT = "SELECT em_iso_pin.nuserie,UPPER(IFNULL(em_animal.codepin, em_iso_pin.codepin)) as 'codepin',em_animal.tel_proprio,em_animal.email_proprio,cd_animals.* FROM em_iso_pin LEFT JOIN em_animal ON em_iso_pin.nuserie = em_animal.nuserie LEFT JOIN cd_animals ON em_animal.id_animal=cd_animals.ani_animal";
if($iso && $iso != "") {
  // search by ISO
  $r = $mysqli->query("SELECT nuserie FROM em_iso_pin WHERE nuserie='$iso'") or die("-2");
  if($r->num_rows > 0) {
    $query = "$LEFT WHERE em_iso_pin.nuserie='$iso'";
  }
  else {
    $query = "SELECT nuserie,codepin,tel_proprio,email_proprio,cd_animals.* FROM em_animal LEFT JOIN cd_animals ON em_animal.id_animal=cd_animals.ani_animal WHERE em_animal.nuserie='$iso'";
  }

  //echo "$query\r\n";
}
else {
  // search by medal
  $r = $mysqli->query("SELECT nuserie,codepin FROM em_iso_pin WHERE codepin='$code'") or die("-2");
  if($r->num_rows > 0) {
    $row = $r->fetch_row();
    $isofix = current($row);
    $query = "$LEFT WHERE em_iso_pin.codepin='$code' XOR em_animal.codepin='$code' XOR (em_iso_pin.codepin='$code' AND em_animal.codepin='$code')";
  }
  else {
    $query = "SELECT nuserie,codepin,tel_proprio,email_proprio,cd_animals.* FROM em_animal LEFT JOIN cd_animals ON em_animal.id_animal=cd_animals.ani_animal WHERE em_animal.codepin='$code'";
  }
  //echo $query;
}

$result = $mysqli->query($query) or die("-2");
if($result->num_rows <= 0) {

  if($isofix != "") {
    $iso = $isofix;
  }

  $new = "codepin=$code\r\ntel_proprio=\r\nemail_proprio=\r\n";
  $new = $new . "nuserie=$iso\r\nani_code=\r\nani_phone_owner=\r\nani_mail_owner=\r\nani_phone_vet=\r\nani_mail_vet=\r\n";
  $new = $new . "ani_reminder_0=\r\nani_reminder_1=\r\nani_reminder_2=\r\nani_reminder_3=\r\nani_reminder_4=\r\nani_reminder_5=\r\n";
  $new = $new . "ani_bottom_0=\r\nani_bottom_1=\r\n";
  $new = $new . "ani_vaccine_0=\r\nani_vaccine_1=\r\nani_vaccine_2=\r\nani_vaccine_3=\r\nani_vaccine_4=\r\n";  
  $new = $new . "ani_vaccine_5=\r\nani_vaccine_6=\r\nani_vaccine_7=\r\nani_vaccine_8=\r\nani_vaccine_9=\r\n";  
  $new = $new . "ani_rw=\r\n";
  die($new);
}

$i = 0;
$fields = array();
while ($i < $result->field_count) {
  $fields[$i] = $result->fetch_field();
	$i = $i + 1;
}

$i = 0;
$values = array();
while ($row = $result->fetch_row()) {
	$count = count($row);
	$y = 0;
	while ($y < $count)
	{
		$c_row = current($row);
		$values[$y] = $c_row;
		next($row);
		$y = $y + 1;
	}
	$i = $i + 1;
	break; // unique
}

$max = count($fields);
$i = 0;
while($i < $max) {
  $field = $fields[$i];
  $value = $values[$i];
  if($field->type == 10) {
    if($value == '0000-00-00') {
      $value = "";
    }
    else {
      $date = new DateTime($value);
      $now = new DateTime("now");
      $unow = $now->format('U');
      $udate = $date->format('U');
      $value = ceil(($udate - $unow) / (24*60*60));
      if($value < 0) {
        $value = 0; // TODO: show negative number instead ?
      }
    }
  }
  echo $field->name . '=' . $value . "\r\n";
	$i = $i + 1;
}

?>

