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

$inters = array();
$anis = array();
$ems = array();
foreach ($_POST as $key => $value) {
  if(eregi("^ani_reminder", $key) || eregi("^ani_bottom", $key) || eregi("^ani_vaccine", $key)) {
    $key = array_search($key, $inter2name);
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
    $inters[$key] = "'$value'";
  }
  else if(eregi("^ani", $key)) {
    $anis[$key] = "'$value'";
  }
  else {
    $ems[$key] = "'$value'";
  }
}

// DEBUG
/*foreach($base as $key => $value) {
  echo "$key=$value\r\n";
}
die("TEST");*/

// GET ISO
$iso = $_POST["nuserie"];
if(!$iso || ($iso == "")) {
  die("-5");
}

// GET CODEPIN : IF NONE GET IT FROM FELIXCAN DATABASE
$codepin = $_POST["codepin"];
if($codepin && $codepin != "") {
  $r = $mysqli->query("SELECT codepin FROM em_iso_pin WHERE UPPER(codepin)=UPPER('$codepin')") or die("-11");
  if($r->num_rows == 0) { 
    $r = $mysqli->query("SELECT codepin FROM em_pin_free WHERE UPPER(codepin)=UPPER('$codepin')") or die("-11");
    if($r->num_rows == 0) {
      die("-11");
    }
  }
}

// SELECT EM
$em_keys = array_keys($ems);
$em_values = array_values($ems);
$query = "SELECT id_animal FROM em_animal WHERE nuserie='$iso'";
$result = $mysqli->query($query) or die("-6");
$row = $result->fetch_row();
if($row) {
  // WE NEED TO UPDATE
  $ani = $row[0];

  $updates = '';
  $i = 0;
  foreach($ems as $key => $value) {
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
  // WE NEED TO INSERT
  $jkeys = join(",", $em_keys);
  $jvalues = join(",", $em_values);
  $query = "INSERT INTO em_animal($jkeys) VALUES($jvalues)"; 
  $result = $mysqli->query($query) or die("-8" . $mysqli->error);
  $ani = $mysqli->insert_id;
  //die("insert: " . $ani);
}

// ADDING EXTERN KEY
$anis["ani_animal"] = $ani;
$ani_keys = array_keys($anis);
$ani_values = array_values($anis);
$jkeys = join(",", $ani_keys);
$jvalues = join(",", $ani_values);
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
$result = $mysqli->query($ani_query) or die("-9");
if($result != "1") {
  die("-10");
}

// EXPORT INTERVENTIONS
$removes = array();
foreach($inters as $key => $value) {
  //$matches = array();
  //preg_match('/inter_(\d+)/', $key, $matches);
  //echo $matches[0] . " : $value\r\n";
  if(preg_match('/(?<name>\w+)_(?<inter>\d+)/', $key, $matches)) {
    //print_r($matches);
    $inter_id = $matches["inter"];
    //echo "$inter_id => $value";
    if($value == "'0000-00-00'") {
      //echo "DELETE $inter_id\r\n";
      $removes[] = $inter_id;
    }
    else {
      $query = "SELECT * FROM cd_reminders WHERE ani_animal=$ani AND inter_id=$inter_id";
      $result = $mysqli->query($query);
      $row = $result->fetch_row();
      if($row) {
        // UPDATE
        $query = "UPDATE cd_reminders SET rem_date=$value WHERE ani_animal=$ani AND inter_id=$inter_id";
        //echo "$query\r\n";
        $mysqli->query($query);
      }
      else {
        // INSERT
        $query = "INSERT INTO cd_reminders(ani_animal, inter_id, rem_date) VALUES ($ani, $inter_id, $value)";
        //echo "$query\r\n";
        $mysqli->query($query);
      }
    }
  }
}
if(count($removes) > 0) {
  $jrems = join(",", $removes);
  $query = "DELETE FROM cd_reminders WHERE ani_animal=$ani AND inter_id in ($jrems)";
  //echo $query;
  $mysqli->query($query);
}

echo "0"; // SUCCESS

?>
