DRPS WIP

<pre>
<?php

require_once(__CA_MODELS_DIR__."/ca_object_representations.php");
require_once(__CA_MODELS_DIR__."/ca_sets.php");

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$set=intval($_REQUEST["set"]);
if(isset($_REQUEST["about"])){
  $about="'".$_REQUEST["about"]."'";
}else{
  $about="null";
}

$o_set=new ca_sets($set);
$sids = $o_set->getItems(['idsOnly' => true]);
$data=array();


foreach($sids as $id){
  $o_obj=new ca_objects($id);
  //echo "<br>".$id." ";

  $rep=0;
  $SQL1 = "select  representation_id from ca_objects_x_object_representations where object_id=".$id." order by rank";
  $dotaz1 = $pdo->query($SQL1);
    foreach($dotaz1  as $Row1){
    $rep++;

    $SQL2 = "select  entity_id from ca_objects_x_entities where object_id=".$id ." and entity_id not in (select entity_id from ca_entities where deleted=1) ";
    $dotaz2 = $pdo->query($SQL2);
    foreach($dotaz2  as $Row2){
      //echo "r".$Row1["representation_id"]." ";
      //echo "E".$Row2["entity_id"]." ";

      $r=new ca_object_representations($Row1["representation_id"]);
      ///var_export($r->getFileList($Row1["representation_id"],null,null, array("returnAllVersions"=>true)));
      
      //var_export(
      $m=$r->getRepresentationMediaForIDs(array($Row1["representation_id"]),array("preview", "small", "medium", "large")  );
      //var_export($m[$Row1["representation_id"]]['urls']);
      $p= $m[$Row1["representation_id"]]['urls'];
      $data[$rep][]= "INSERT INTO `victim_images` ( `preview`, `small`, `medium`, `large`, `type`, `victim_id`, `object_id`, `idno`, `about`) SELECT '".$p['preview']."','".$p['small']."','".$p['medium']."','".$p['large']."',2, id, ".$id.", null,".$about." FROM victim where entity_id=".$Row2["entity_id"]." or id_external=".$Row2["entity_id"].";"."<br>/*".$m[$Row1["representation_id"]]['tags']['preview']."*/<br><br>";
      
    }


  }


}


foreach ($data as $l1){
  echo "<hr>";
  foreach($l1 as $l2){
    echo $l2;
  }


}