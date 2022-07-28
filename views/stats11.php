Idno regexp test
<pre><?php
//include "setup.php";
require_once(__CA_LIB_DIR__.'/Configuration.php');



$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}




$o_config = new Configuration(__CA_BASE_DIR__.'/app/conf/local/search.conf', false, true);
$settings=$o_config->get("idno_regexes");
unset($settings["ca_object_lots"]);

foreach($settings as $table=>$regexes){
  echo "<br>";
 $SQL= "SELECT idno".($table=="ca_object_lots"?"_stub":"")." from ".$table." WHERE deleted=0";

  $dotaz = $pdo->query($SQL);
  $ok=array();
  $ko=array();

  foreach($dotaz  as $Row){
	$th=0;
	//foreach($regexes as $reg){
	if(preg_match("#".$regexes."#",$Row["idno"])){
		$th=1;
	}
	//}
	if($th){
		$ok[]=$Row["idno"];
	}else{
		$ko[]=$Row["idno"];
		echo $Row["idno" ]."\n";
	}
}

echo $table. "_OK: ".count($ok).", KO: ".count($ko)."\n";


}
 ?></pre>