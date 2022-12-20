DRPS WIP

<pre>
<?php

require_once(__CA_MODELS_DIR__."/ca_object_representations.php");
require_once(__CA_MODELS_DIR__."/ca_sets.php");

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

global $pdo;
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

function getEntitiesIdentical($id,$recursive=true){
    $result=array($id=>$id);
    global $pdo;
    $SQL1 = "select * from ca_entities_x_entities  where type_id=96
    and   (
      entity_left_id=".$id."  or 
      entity_right_id=".$id."  
    )
    ";
  $dotaz1 = $pdo->query($SQL1);
    foreach($dotaz1  as $Row1){
      foreach(array('entity_left_id','entity_right_id') as $ident){
          $result[$Row1[$ident]]=$Row1[$ident];
          if($recursive==true && ($Row1[$ident]!=$id)){
               $result=array_merge($result, getEntitiesIdentical( $Row1[$ident], false ) );
          }
      }
  }

  return $result;
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

    $ids=array();  

    
    $r=new ca_object_representations($Row1["representation_id"]);
    $m=$r->getRepresentationMediaForIDs(array($Row1["representation_id"]),array("preview", "small", "medium", "large")  );
    
    $p= $m[$Row1["representation_id"]]['urls'];  

    $SQL2 = "select  entity_id from ca_objects_x_entities where object_id=".$id ." and entity_id not in (select entity_id from ca_entities where deleted=1) ";
    $dotaz2 = $pdo->query($SQL2);

    foreach($dotaz2  as $Row2){
      //echo "r".$Row1["representation_id"]." ";
      //echo "E".$Row2["entity_id"]." ";
      
      $ids=array_merge($ids,getEntitiesIdentical($Row2["entity_id"])) ;     
      
      
    }

    $ids_e=implode(", ", $ids );
    $data[$rep][]= "INSERT INTO `victim_images` ( `preview`, `small`, `medium`, `large`, `type`, `victim_id`, `object_id`, `idno`, `about`) SELECT '".$p['preview']."','".$p['small']."','".$p['medium']."','".$p['large']."',2, id, ".$id.", null,".$about." FROM victim where entity_id in(".$ids_e.") or id_external  in(".$ids_e.");"."<br>/*".$m[$Row1["representation_id"]]['tags']['preview']."*/<br><br>";
  }


}


foreach ($data as $l1){
  echo "<hr>";
  foreach($l1 as $l2){
    echo $l2;
  }


}