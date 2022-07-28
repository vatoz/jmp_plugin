<?php
echo "Kolikrát je použit attribut jako interstitioal field<br>";
//include "setup.php";
echo "<textarea cols=80 rows=100>";
$tables=array( 14,15,21,22,23,24,26,30,34,52,53,54,55,58,59,61,62,63,64,65,68,69,70,73,74,75,76,85,86,87);

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}


$data=array();

  $SQL= "SELECT * from ca_metadata_elements";
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    $data[$Row["element_id"]] = $Row;
  }


  $SQL= "SELECT count(*) as cnt, element_id from ca_attributes  where table_num in(" .implode(", ",$tables)  .") group by element_id";

  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    $data[$Row["element_id"]]["cnt"] = $Row["cnt"];
  }



  echo "id;code;datatype;cnt\n";

foreach($data  as $Row){
  if($Row["cnt"]) echo $Row["element_id"].";".$Row["element_code"].";".$Row["datatype"].";".$Row["cnt"]."\n";
}

echo "</textarea>";
