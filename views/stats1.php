<?php

//include "../setup.php";

$tables=array( 	"ca_collections_x_collections"			,  	"ca_collections_x_vocabulary_terms"	,  	"ca_entities_x_collections"					,
  	"ca_entities_x_occurrences"					,  	"ca_entities_x_places"						,
  	"ca_entities_x_vocabulary_terms"	,  	"ca_entities_x_entities"					,
  	"ca_object_lots_x_collections"		,  	"ca_object_lots_x_entities"				,
  	"ca_object_lots_x_occurrences"		,  	"ca_object_lots_x_places"					,
  	"ca_objects_x_collections"				,  	"ca_objects_x_entities"						,
  	  	"ca_objects_x_objects"						,
  	"ca_objects_x_occurrences"				,  	"ca_objects_x_places"						,
  	"ca_objects_x_vocabulary_terms"	,  	"ca_occurrences_x_collections"			,
  	"ca_occurrences_x_occurrences"			,  	"ca_occurrences_x_vocabulary_terms"		,
  	"ca_places_x_collections"				,  	"ca_places_x_occurrences"				,
  	"ca_places_x_places"						,  	"ca_places_x_vocabulary_terms"	,
  	"ca_loans_x_objects"						,  	"ca_loans_x_entities"
);




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
foreach ($tables as $table){
  $SQL= "SELECT type_id, count(*) as cnt from ".$table." group by type_id";
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    if(!isset($data[$Row["type_id"]])){
      $data[$Row["type_id"]]=0;
    }
    $data[$Row["type_id"]] += $Row["cnt"];
  }
}
  echo "Seznam typů vztahů, a kolikrát je použit<br>CSV<br>"; 
  echo "<textarea cols=80 rows=100>";
  echo "id;type;tam;zpet;cnt\n";

$SQL="select ca_relationship_types.type_id, type_code,typename,typename_reverse from ca_relationship_types  left join  ca_relationship_type_labels on ca_relationship_types.type_id = ca_relationship_type_labels.type_id where locale_id=1 or locale_id is null order by  ca_relationship_types.type_id asc ";
  $dotaz = $pdo->query($SQL);
foreach($dotaz  as $Row){
      echo " ".$Row["type_id"]. ";".$Row["type_code"].";".
$Row["typename"].";".$Row["typename_reverse"].";";


      ;
    if(!isset($data[$Row["type_id"]])){
      //$data[$Row["type_id"]]=0;
      echo "0";

    }else{
  echo $data[$Row["type_id"]];

    }
    echo "\n";


}
echo "</textarea>";


