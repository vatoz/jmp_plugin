Test images
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

  $SQL= "SELECT * from ca_object_representations 
  where 
    representation_id > ".$from . " 
    and deleted=0 
    and representation_id not in(select representation_id from ca_object_representation_multifiles) 
    and mimetype not in ('audio/mpeg','application/octet-stream','audio/mp4','audio/x-wav','video/avi','video/mp4','video/mpeg',
    'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/octet-stream','text/xml'

    )
  
  order by representation_id ASC LIMIT 99 ";
  error_log( $SQL);
  $cnt=0;
  $dotaz = $pdo->query($SQL);
  $nok=array();
  
  foreach($dotaz  as $Row){
    $i=$Row["representation_id"];
    $cnt++;

    $t_media = new ca_object_representations($i);
    $vs_fldname = 'media';
    $va_tilepic_info = $t_media->getMediaInfo($vs_fldname, 'tilepic');
    
	if(is_null($va_tilepic_info)){        
        $nok[]=$i;
        echo "X";
			
	}else{
        echo ".";
        
    }
    if($cnt % 50 ==0 ) echo "<br>";


      
  }
  if(count($nok)){
    foreach ($nok as $p){
        echo "su -s /bin/sh www-data -c 'php support/bin/caUtils reprocess-media -k tilepic -i ". $p."'"."<br>";
        file_put_contents("errorsfound.txt", "su -s /bin/sh www-data -c 'php support/bin/caUtils reprocess-media -k tilepic -i ". $p."'\n", FILE_APPEND | LOCK_EX);

    }


  }


  echo '<br><a id=automat href="/index.php/jmp/Stats/ThumbTester?from='.$i.'">Další</a> ';

  if($cnt ){
     echo "<script>document.getElementById('automat').click(); </script>";
  }else{
    echo "End";

  }