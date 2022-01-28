
Řada klíčových slov má variantu doktor a variantu doktorka.  Kolik se takových používá?  
<?php

//include "setup.php";


$tablesdatamodel=array(
	"ca_collections_x_vocabulary_terms"			=> 15,
	"ca_entities_x_vocabulary_terms"			=> 24,	
	"ca_objects_x_vocabulary_terms"				=> 65,
	"ca_occurrences_x_vocabulary_terms"			=> 70,
	"ca_places_x_vocabulary_terms"				=> 76,
	"ca_object_lots_x_vocabulary_terms"			=> 115,
	"ca_object_representations_x_vocabulary_terms"		=> 116,
	"ca_loans_x_vocabulary_terms" => 183
	

);

$data=array();

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;


try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}


foreach (array_keys($tablesdatamodel) as $table){
  $stub="";
  $base=substr($table,0, strpos($table,"_x_"));
  $singular=substr($base, 3,-1);
  switch ($singular){
    case "entitie":
      $singular="entity";
      break;
  case "object_lot":
      $stub="_stub  as idno ";
      $singular="lot";
      break;
  case "object_representation":      
      $singular="representation";
      break;
      
      default:
      
  }
  echo "<h1>".$base."</h1>";
  $Query="SELECT ".$base.".idno".$stub." from ".$base." INNER JOIN ".$table ." on  ".$base.".".$singular."_id = ".$table.".".$singular."_id 
  WHERE ".$table.".item_id in (SELECT item_id
FROM `ca_list_items`
WHERE `parent_id` IN (1785,1319))
  
  ";
  echo "<pre>".$Query."</pre>"; 
  
  $dotaz = $pdo->query($Query);
  foreach($dotaz  as $Row){
    $t= $Row['idno'];
    echo $t." ";
    
    $k=strpos($t,".",strpos($t,".")+1);
    $ttt=substr($t,0,$k);
    if(!isset($data[$ttt])){
      $data[$ttt]=0;
    }
    $data[$ttt]++;    
  }  
}


echo "<h1>souhrn</h1>";
foreach ($data as $key=>$cnt){
    echo $key. " <strong>x ".$cnt."</strong><br>";
  
}



