Oprava vyhledávání
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

function subpartrev($separator,$string){
    $pos=strrpos($string,$separator);
    return substr($string,$pos+strlen($separator));

}

$nok=false;
$SQL="select object_id, idno from ca_objects where deleted=0 and idno not in(
    SELECT word
    FROM `ca_sql_search_word_index` as swi inner join 
    ca_sql_search_words as sw on  sw.word_id =swi.word_id
    WHERE swi.`field_table_num` = '57' AND swi.`table_num` = '57' AND swi.`field_num` = 'I7' AND
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
            rel_type_id) select 57,".$Row["object_id"].",57,'I7',".$Row["object_id"].", word_id,100,0,0 from ca_sql_search_words where word= '".$variant."'
                      
            ";
            $pdo->query($SQL3);
            //echo $SQL3;

            

        }

  }


  if($nok) {
    echo '<br><br><a id=automat href="/index.php/jmp/Stats/IdnoReindex">Další</a> ';

    //if(!count($nok)) 
    echo "<script>document.getElementById('automat').click(); </script>";
  
  

  };

  


