Oprava vyhledávání
<ul>
<li><a href=?table=ca_objects>objects</a></li>
<li><a href=?table=ca_entities>entities</a></li>
<li><a href=?table=ca_places>places</a></li>

</ul>

<pre>
<?php
//include "setup.php";


$table=isset($_REQUEST['table']) ?$_REQUEST['table']:"ca_objects";


switch($table){
    case "ca_places":
        $table="ca_places";
        $querypart="place_id as id, idno from ca_places";
        $tablenum=72;
        break;


    case "ca_entities":
        $table="ca_entities";
        $querypart="entity_id as id, idno from ca_entities";
        $tablenum=20;
        break;

    case "ca_objects":
    default:
        $table="ca_objects";
        $querypart="object_id as id, idno from ca_objects";
        $tablenum=57;

}


$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

function subpartrev($separator,$string){
    $pos=strrpos($string,$separator);
    return substr($string,$pos+strlen($separator));

}

$nok=false;
$SQL="select ".$querypart." where deleted=0 and idno not in(
    SELECT word
    FROM `ca_sql_search_word_index` as swi inner join 
    ca_sql_search_words as sw on  sw.word_id =swi.word_id
    WHERE swi.`field_table_num` = '".$tablenum."' AND swi.`table_num` = '".$tablenum."' AND swi.`field_num` = 'I7' AND
    swi.row_id=swi.field_row_id
    )  limit 100
";

  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
        echo $Row["idno"]."<br>";
        $variants=array();
        $variants=explode("/",subpartrev(".",$Row["idno"]));
        $variants[]=$Row["idno"];
        $variants[]=subpartrev(".",$Row["idno"]);
        array_unique($variants);

        $nok=true;
        foreach($variants as $variant){
            $variant=strtolower($variant);
            $stem=str_replace("/","",$variant);
            //echo $variant."<br>".$stem."<br><br>";

            $SQL2="INSERT IGNORE INTO ca_sql_search_words (word,stem) values('".$variant."','".$stem."')";
            $pdo->query($SQL2);
            //echo $SQL2."<br>";
            $SQL3="INSERT into ca_sql_search_word_index (table_num,row_id,     field_table_num, field_num, field_row_id,
            word_id,	
            boost,
            access,
            rel_type_id) select ".$tablenum.",".$Row["id"].",".$tablenum.",'I7',".$Row["id"].", word_id,100,0,0 from ca_sql_search_words where word= '".$variant."'        
            ";
            $pdo->query($SQL3);
            //echo $SQL3;

            

        }

  }


  if($nok) {
    echo '<br><br><a id=automat href="/index.php/jmp/Stats/IdnoSearchRebuild?table='.$table.'">Další</a> ';

    //if(!count($nok)) 
    echo "<script>document.getElementById('automat').click(); </script>";
  
  

  };

  


