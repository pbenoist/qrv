<?PHP

include '../config/define.php';

header('Content-Type: text/plain');

$mysqli = new mysqli($SRVNAME, $USER, $PASSWORD, $DBNAME);

if (mysqli_connect_errno()) {
  die("-1");
}

$inter2name = array (
  "0" => "ani_vaccine_0",
  "1" => "ani_vaccine_1",
  "2" => "ani_vaccine_2",
  "3" => "ani_vaccine_3",
  "4" => "ani_vaccine_4",
  "5" => "ani_vaccine_5",
  "6" => "ani_vaccine_6",
  "7" => "ani_vaccine_7",
  "8" => "ani_vaccine_8",
  "9" => "ani_vaccine_9",
  "100" => "ani_reminder_0",
  "101" => "ani_reminder_1",
  "102" => "ani_reminder_2",
  "103" => "ani_reminder_3",
  "104" => "ani_reminder_4",
  "105" => "ani_reminder_5",
  "200" => "ani_bottom_0",
  "201" => "ani_bottom_1"
);

$count = 0;
$query = "SELECT * from cd_animals";
//$query = $query . " LIMIT 2";
echo "$query\r\n";
$result = $mysqli->query($query) or die("1");
while($row = $result->fetch_assoc()) {
  $ani = $row["ani_animal"];
  foreach($row as $key => $value) {
    $inter = array_search($key, $inter2name);
    if($inter) {
      if($value == '0000-00-00') {
      
      }
      else {
        //echo "ani=$ani => $inter=$value\r\n";
        $rem_date = "'$value'";
        $query = "INSERT INTO cd_reminders(ani_animal,inter_id,rem_date) VALUES ($ani, $inter, $rem_date)";
        echo "$query\r\n";
        //$mysqli->query($query) or die("2");
        $count = $count + 1;
      }  
    }
  }
}
echo "COUNT: $count\r\n";

?>

