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

  $new = "";
  $new = $new . "codepin=$code\r\ntel_proprio=\r\nemail_proprio=\r\n";
  $new = $new . "nuserie=$iso\r\nani_code=\r\nani_phone_owner=\r\nani_mail_owner=\r\nani_phone_vet=\r\nani_mail_vet=\r\n";
  $new = $new . "ani_rw=\r\n";
  die($new);
}


// GET "FIELD" -> "VALUE" ASSOCIATIONS AS AN ARRAY
$rows = array();
while($row = $result->fetch_assoc()) {
  foreach($row as $key => $value) {
    //echo "$key=$value";
    $rows[$key] = $value;
  }
}


// REMOVE LEGACY FIELDS
$new_rows = array();
foreach($rows as $key => $value) {
  if(eregi("^ani_reminder", $key) || eregi("^ani_bottom", $key) || eregi("^ani_vaccine", $key)) {
    continue;
  }
  else {
    $new_rows[$key] = $value;
  }
}
$rows = $new_rows;


// DEBUG: SHOW ALL "FIELD" -> "VALUE"
/*foreach($rows as $key => $value) {
  echo "$key=$value\r\n";
}
die("TEST");*/


// SEARCH FOR REMINDERS
$ani = $rows["ani_animal"];
$r = $mysqli->query("SELECT * FROM cd_reminders WHERE ani_animal='$ani'") or die("-2");
while($row = $r->fetch_assoc()) {
  $rem = array();
  foreach($row as $key => $value) {
    //echo "$key=$value";
    $rem[$key] = $value;
  }
  $inter_name = "inter_" . $rem["inter_id"];
  $value = $rem["rem_date"];
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
  $rows[$inter_name] = $value;
}

$inter2name = array (
  "inter_0" => "ani_vaccine_0",
  "inter_1" => "ani_vaccine_1",
  "inter_2" => "ani_vaccine_2",
  "inter_3" => "ani_vaccine_3",
  "inter_4" => "ani_vaccine_4",
  "inter_5" => "ani_vaccine_5",
  "inter_6" => "ani_vaccine_6",
  "inter_7" => "ani_vaccine_7",
  "inter_8" => "ani_vaccine_8",
  "inter_9" => "ani_vaccine_9",
  "inter_100" => "ani_reminder_0",
  "inter_101" => "ani_reminder_1",
  "inter_102" => "ani_reminder_2",
  "inter_103" => "ani_reminder_3",
  "inter_104" => "ani_reminder_4",
  "inter_105" => "ani_reminder_5",
  "inter_200" => "ani_bottom_0",
  "inter_201" => "ani_bottom_1"
);

// SHOW RESULT
foreach($rows as $key => $value) {
  /*if(eregi("^ani_reminder", $key) || eregi("^ani_bottom", $key) || eregi("^ani_vaccine", $key)) {
    // LEGACY FIELDS
    continue;
}*/
  if(eregi("^inter_", $key)) {
    $key = $inter2name[$key];
  }
  echo "$key=$value\r\n";
}

?>

