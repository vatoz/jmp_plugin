CSV export
<br><textarea rows="34" cols="99">
<?php


$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;


try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}





  $Query="SELECT *  FROM `ca_places` inner join ca_place_labels on ca_places.place_id= ca_place_labels.place_id   WHERE `hierarchy_id` = '162' AND `parent_id` = '1111' AND `deleted` = '0'    and ca_place_labels.locale_id=1 ";
    
  $dotaz = $pdo->query($Query);
  foreach($dotaz  as $Row){

    

  $Query2="SELECT *  FROM `ca_places` inner join ca_place_labels on ca_places.place_id= ca_place_labels.place_id   WHERE `hierarchy_id` = '162' AND `parent_id` = ".$Row["place_id"]." AND `deleted` = '0'  and ca_place_labels.locale_id=1   ";
    
  $dotaz2 = $pdo->query($Query2);
  foreach($dotaz2  as $Row2){

    echo $Row["idno"].";".$Row["name"].";".$Row2["idno"].";".$Row2["name"]."\n";

    
  }  
}



?></textarea>