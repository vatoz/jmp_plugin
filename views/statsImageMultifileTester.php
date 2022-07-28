Test multifiles
WIP

<pre>
<?php
function bigintval($value) {
  $value = trim($value);
  if (ctype_digit($value)) {
    return $value;
  }
  $value = preg_replace("/[^0-9](.*)$/", '', $value);
  if (ctype_digit($value)) {
    return $value;
  }
  return 0;
}

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

$from=bigintval($_REQUEST["from"]);
$i=$from;
  $SQL= "SELECT * from ca_object_representation_multifiles 
  where 
    representation_id >= ".$from . " 
    
  order by representation_id ASC LIMIT 4000";

  $dotaz = $pdo->query($SQL);
  $nok=array();
  foreach($dotaz  as $Row){
    $i=$Row["representation_id"];
    
    $t_media = new ca_object_representation_multifiles();
    
    $t_media->load(['representation_id' => $i, 'resource_path' => $Row["resource_path"]]);
    $vs_fldname = 'media';
    $va_tilepic_info = $t_media->getMediaInfo($vs_fldname, 'tilepic');
    
	if(is_null($va_tilepic_info)){        
        $nok[$i]=$i;
       // echo "<br>".$i.":". $Row["resource_path"]. "<br>";
			
	}else{
    //echo  "<small>".$i.":". $Row["resource_path"]. "</small>";
    }



      
  }
  echo "<hr>";
  
  if(count($nok)){
    foreach ($nok as $p){
        echo "su -s /bin/sh www-data -c 'php support/bin/caUtils reprocess-media -k tilepic -i ". $p."'"."<br>";

        file_put_contents("errorsfound.txt", "su -s /bin/sh www-data -c 'php support/bin/caUtils reprocess-media -k tilepic -i ". $p."'\n", FILE_APPEND | LOCK_EX);
    }
  }



  if(isset($nok[$i]) )$i++;

  echo '<br><br><a id=automat href="/index.php/jmp/Stats/MultifileTester?from='.$i.'">Další</a> ';

  //if(!count($nok)) 
  echo "<script>document.getElementById('automat').click(); </script>";