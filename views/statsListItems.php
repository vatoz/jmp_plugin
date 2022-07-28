Seznamy a položky
<table>
  <thead>
  <tr>
  <td> element_name a editor<td>
  <td>element_code</td>
  <td>documentation_url</td>
  <td>list_id</td>
  <td>list_code</td>
  <td>list name</td>
  <td>count</td>
  </tr>
</thead>
  
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

$li=array();
$SQL="SELECT cl.list_id,cl.list_code, ca.name
FROM `ca_lists` cl inner join ca_list_labels ca on cl.list_id = ca.list_id where locale_id=1";
$dotaz = $pdo->query($SQL);
foreach($dotaz  as $Row){
  $li[$Row['list_id']]=$Row;
}




$lcn=array();
$SQL="SELECT list_id, count(*) as cnt
FROM `ca_list_items` where deleted=0 group by list_id";
$dotaz = $pdo->query($SQL);
foreach($dotaz  as $Row){
  $lcn[$Row['list_id']]=$Row['cnt'];
}



  $SQL= "SELECT cs.element_id, cs.settings, cs.element_code,cs.list_id, cl.name,cs.documentation_url
FROM `ca_metadata_elements` cs inner join ca_metadata_element_labels cl on cs.element_id = cl.element_id
where locale_id=1 and list_id is not null";


  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
//	if(strpos(base64_decode($Row['settings']),'"requireValue";s:1:"1";')!==false){
		//echo "<a href='https://ca.jewishmuseum.cz/index.php/administrate/setup/Elements/Edit/element_id/".$Row['element_id']."'>".$Row["name"]."</a><br>";
echo "<tr>";
echo "<td><a href='/index.php/administrate/setup/Elements/Edit/element_id/".$Row['element_id']."'>".$Row["name"]."</a>"."<td>";
echo "<td>".$Row['element_code']."</td>";
echo "<td><a href='" . $Row['documentation_url']."' >".$Row['documentation_url'].  "</a></td>";
echo "<td><a href='/index.php/administrate/setup/list_editor/ListEditor/Edit/list_id/".$Row['list_id']."'>".$Row['list_id']."</a></td>";
echo "<td>".$li[$Row['list_id']]['list_code']."</td>";
echo "<td>".$li[$Row['list_id']]['name']."</td>";
echo "<td>".$lcn[$Row['list_id']]."</td>";
echo "</tr>";




}
?></table>