<?PHP

include '../config/define.php';

header('Content-Type: text/plain');

$mysqli = new mysqli($SRVNAME, $USER, $PASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
	die("-1");
}

$number = $mysqli->real_escape_string($_GET["number"]);
$key = $mysqli->real_escape_string($_GET["key"]);
//echo "number=" . $number . "\r\n";
//echo "key=" . $key . "\r\n";

if(!$number or !$key or strlen($number) < 1 or strlen($key) != 12) {
  die("-2"); // bad args
}

$query = "SELECT vet_number FROM cd_veterinarians WHERE vet_number=$number and vet_key='$key'";
$result = $mysqli->query($query) or die("-3");
if($result->num_rows <= 0) {
	die("-4");
}

// number and key are OK. Inserting/Updating record.

// REPLACE IN em_animal : codepin, tel_proprio, email_proprio
// REPLACE IN cd_animals : ani_*

$keys = array();
$values = array();
$i = 0;
foreach ($_POST as $key => $value) {
  $keys[$i] = $key;

  if($key == 'nuserie') {
    $iso = $value;
  }

  if($key == 'codepin') {
    $codepin = $value;
  }

  if(strpos($key, 'ani_reminder') === 0 or strpos($key, 'ani_bottom') === 0 or strpos($key, 'ani_vaccine') === 0) {
    if($value == "") {
      $value = '0000-00-00';
    }
    else {
      $days = intval($value);
      $now = new DateTime("now");
      $now->modify("+" . $days . " day");
      //echo $now->format('Y-m-d');
      $value = $now->format('Y-m-d');
    }
  }

  $values[$i] = "'$value'";
  $i = $i + 1;
}

//echo join(",", $keys);

if(!in_array("nuserie", $keys)) {
  die("-5");
}

if($codepin && $codepin != "") {
  $r = $mysqli->query("SELECT codepin FROM em_iso_pin WHERE UPPER(codepin)=UPPER('$codepin')") or die("-11");
  if($r->num_rows == 0) { 
    $r = $mysqli->query("SELECT codepin FROM em_pin_free WHERE UPPER(codepin)=UPPER('$codepin')") or die("-11");
    if($r->num_rows == 0) {
      die("-11");
    }
  }
}

// separation des clefs des tables cd_animals et em_animal
$em_keys = array();
$em_values = array();
$ani_keys = array();
$ani_values = array();
$i = 0;
foreach ($keys as $key) {
  if(strpos($key, 'ani_') === 0) {
    $ani_keys[] = $key;
    $ani_values[] = $values[$i]; 
  }
  else {
    $em_keys[] = $key;
    $em_values[] = $values[$i];
  }
  $i = $i + 1;
}

// SELECT

$jkeys = join(",", $em_keys);
$jvalues = join(",", $em_values);

$query = "SELECT id_animal FROM em_animal WHERE nuserie='$iso'";
$result = $mysqli->query($query) or die("-6");
$row = $result->fetch_row();
if($row) {
  // need an update
  $ani = $row[0];
  
  $updates = '';
  $i = 0;
  foreach($em_keys as $key) {
    $value = $em_values[$i];
    if($i > 0) {
      $updates = $updates . ', '; 
    }
    $updates = $updates . "$key = $value";
    $i = $i + 1;
  }
  $query = "UPDATE em_animal SET $updates WHERE id_animal=$ani";
  //echo $query;
  $result = $mysqli->query($query) or die("-7");
  //die("update: " . $ani);
}
else {
  // need an insert
  $query = "INSERT INTO em_animal($jkeys) VALUES($jvalues)"; 
  $result = $mysqli->query($query) or die("-8" . $mysqli->error);
  $ani = $mysqli->insert_id;
  //die("insert: " . $ani);
}

// ajout de la clef externe
$ani_keys[] = "ani_animal";
$ani_values[] = $ani;
$jkeys = join(",", $ani_keys);
$jvalues = join(",", $ani_values);
//$ani_query = "REPLACE INTO cd_animals($jkeys) VALUES($jvalues)";

$updates = '';
$i = 0;
foreach($ani_keys as $key) {
  $value = $ani_values[$i];
  if($i > 0) {
    $updates = $updates . ', ';
  }
  $updates = $updates . "$key = $value";
  $i = $i + 1;
}
$ani_query = "INSERT INTO cd_animals ($jkeys) VALUES($jvalues) ON DUPLICATE KEY UPDATE $updates";

//echo $ani_query;

// transaction
$result = $mysqli->query($ani_query) or die("-9");
if($result != "1") {
  die("-10");
}

echo "0"; // SUCCESS

?>
