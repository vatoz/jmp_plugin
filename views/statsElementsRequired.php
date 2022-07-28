Elementy s vyžadovanou hodnotou,  asi obsolete
<pre>
<?php

//include "setup.php";

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}


  $SQL= "SELECT cs.element_id, cs.settings, cs.element_code, cl.name
FROM `ca_metadata_elements` cs inner join ca_metadata_element_labels cl on cs.element_id = cl.element_id 
where locale_id=1 and list_id is not null";


  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
	if(strpos(base64_decode($Row['settings']),'"requireValue";s:1:"1";')!==false){
		echo "<a href='/index.php/administrate/setup/Elements/Edit/element_id/".$Row['element_id']."'>".$Row["name"]."</a><br>";
}


}


