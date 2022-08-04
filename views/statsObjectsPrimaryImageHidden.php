některé předměty na collections mají v popisu obrázku „Omlouváme se, ale náhled není k dispozici“ – v CA mají jako primární médium médium neveřejné; šlo by nějak nastavit, aby u médií bylo vždy primární to médium, které je jako první veřejné?

<pre>
<?php

require_once(__CA_MODELS_DIR__."/ca_object_representations.php");

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

//megahaluz, několikrát vnořený dotaz co získá seznam objektů, které mají primární obrázek neviditelný, ale mají nějaký, který je omezený nebo veřejný

  $SQL= "
  select  ca_objects.object_id, ca_objects.idno,  ca_objects_x_object_representations.representation_id, ca_object_representations.access
  
  from 
ca_objects_x_object_representations
left join  ca_object_representations 
on ca_objects_x_object_representations.representation_id 
=  ca_object_representations.representation_id 
left join ca_objects on ca_objects_x_object_representations.object_id=ca_objects.object_id

where 
ca_objects.access<>1435
 and ca_objects_x_object_representations.object_id in (




select object_id from ca_objects_x_object_representations  where object_id
in (

select object_id from ca_objects_x_object_representations where representation_id in(

select representation_id from ca_object_representations where representation_id  in(
SELECT representation_id
FROM `ca_objects_x_object_representations` where is_primary=1) and
access=1435))

group by object_id having count(*) >1) order by  ca_objects.object_id
  
  ";



  $cnt=0;
  $dotaz = $pdo->query($SQL);
  $nok=array();
  $last="";
  foreach($dotaz  as $Row){

    if($Row["idno"]!==$last){
      echo "<hr> <a href='/index.php/editor/objects/ObjectEditor/Edit/object_id/".
      $Row["object_id"]."'>". $Row["idno"]."</a>";
      $last=$Row["idno"];
    }



    $t_media = new ca_object_representations( $Row["representation_id"]);
    $m=$r->getRepresentationMediaForIDs(array( $Row["representation_id"]),array("preview")  );
    //var_export($m[$Row1["representation_id"]]['urls']);
    $p= $m[$Row1["representation_id"]]['urls'];
    $style= $Row["access"]==1433?" border:thin solid green":(
      $Row["access"]==1434?"border:thin solid yellow; opacity:0.9;":"border:thin solid red; opacity:0.5;"
    );

    echo '<img style="'.$style.'" src="'. $p['preview'].'" >';
        
  
  }