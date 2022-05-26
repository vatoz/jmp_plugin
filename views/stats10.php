
Jak šel čas s importem obrázků?<br>
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



function showdata($Verze){
  
  
  $dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
  $user = __CA_DB_USER__;
  $password = __CA_DB_PASSWORD__;
  
  
  try {
      $pdo = new PDO($dsn, $user, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      die('Connection failed: ' . $e->getMessage());
  }
$Years=array(2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022);

echo "<table border=1 id=ttt_".$Verze." >";
echo "<tr>";
echo "<td>&nbsp;</td>";
foreach ($Years as $Rok){
		echo "<td>".$Rok."</td>";
	
}
echo"</tr>";


$Query="SELECT idno, item_id FROM `ca_list_items` WHERE `list_id` = '12'";
$dotaz_typ = $pdo->query($Query);
foreach($dotaz_typ  as $Typ){
	
	switch($Verze){
		case 1:
		$Query="SELECT count(distinct ca_objects.object_id) as digits, YEAR(FROM_UNIXTIME(log_datetime)) as representation_creation FROM ca_objects inner join ca_objects_x_object_representations on  ca_objects.object_id = ca_objects_x_object_representations.object_id	
		inner join `ca_change_log` on representation_id=logged_row_id  where  logged_table_num =56 and changetype = 'I'  
		and type_id = 
		".$Typ['item_id']." group by representation_creation";
		
	
	
		
		break;
		case 2:
		//tahle query je v pořádku
		$Query="SELECT count(*) as digits, YEAR(FROM_UNIXTIME(log_datetime)) as representation_creation FROM ca_objects inner join ca_objects_x_object_representations on  ca_objects.object_id = ca_objects_x_object_representations.object_id	
		inner join `ca_change_log` on representation_id=logged_row_id  where  logged_table_num =56 and changetype = 'I'  
		and type_id = 
		".$Typ['item_id']." group by representation_creation";
		
		
		break;
	}
	
	echo "<tr><td>".$Typ["idno"]."</td>";
	
	$yc=$Years;
	$dotaz_data = $pdo->query($Query);
	$rok=array_shift($yc);
	
	foreach($dotaz_data as $Data){
		while($rok<intval($Data['representation_creation'])){
			echo "<td>&nbsp;</td>";
			$rok=array_shift($yc);
		}
		echo "<td><strong>".$Data["digits"]."</td>";
		$rok=array_shift($yc);
	}
	
	echo "</tr>";
}
echo "</table>";

}

echo "Kolik bylo zdokumentovaných objektů?";
showdata(1);
echo "<hr>";
echo "Kolik bylo vloženo reprezentací? (Tedy o něco větší číslo.)";
showdata(2);

