CSV export
<br><textarea rows="34" cols="99">
<?php
echo "Jméno\tIdno\n";

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;


try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}





  $Query="SELECT displayname, idno  FROM `ca_entities` inner join ca_entity_labels on ca_entities.entity_id= ca_entity_labels.entity_id   WHERE  `deleted` = '0'    and ca_entity_labels.locale_id=1  and  ca_entities.type_id in(63,67)
  order by length(displayname) desc
  ";
    
  $dotaz = $pdo->query($Query);
  foreach($dotaz  as $Row){

    

    echo $Row["displayname"]."\t".$Row["idno"]."\n";

    

}



?></textarea>