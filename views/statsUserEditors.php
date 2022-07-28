<?php
echo "Který uživatel používá které editory";
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

function recursive_unset(&$array, $unwanted_key) {
    unset($array[$unwanted_key]);
    foreach ($array as &$value) {
        if (is_array($value)) {
            recursive_unset($value, $unwanted_key);
        }
    }
}

$analyze=array();
$data=array();
function sumad($val){
  global $summary;
  if(!isset($summary[$val])) $summary[$val]=0;
  $summary[$val]++;
}

function adA($key,$fields){
  global $analyze;
  if(is_array($fields)){
    foreach ($fields as $k => $v) {
      if(!isset($analyze[$key][$k][$v]) ){  $analyze[$key][$k][$v]=0;      }
      $analyze[$key][$k][$v]++;
      sumad($v);

    }
  }else{
    if(isset($analyze[$key])){
    foreach($analyze[$key] as $k=>$v){
      if(!isset($v[$fields])) $analyze[$key][$k][$fields]=0;
      $analyze[$key][$k][$fields]++;
      sumad($fields);
      }
    }
  }
}
$summary=array();

$labely=array();
$SQL= "SELECT ui_id, name FROM `ca_editor_ui_labels` WHERE `locale_id` = '1'";
$dotaz = $pdo->query($SQL);
foreach($dotaz  as $Row){
  $labely[$Row["ui_id"]]=$Row["name"];
}

$SQL= "SELECT * FROM `ca_editor_uis` WHERE `is_system_ui` = '1'";
$dotaz = $pdo->query($SQL);
foreach($dotaz  as $Row){
  $summary[$Row["ui_id"]]=0;
}



$SQL= "SELECT * from ca_users where active=1";
$data=array();
$keys=array();
$users=array();
  $dotaz = $pdo->query($SQL);
  $stats_u=0;
    $stats_p=0;
    $stats_pr=0;
  foreach($dotaz  as $Row){
      $stats_u++;
      $users[$Row["user_id"]]=$Row["user_name"];
    //echo "<h1>".$Row["user_id"]."-".$Row["user_name"]."</h1>";

    //echo "<pre>";
    $d=unserialize(base64_decode($Row["vars"]));
    recursive_unset($d,"result_list");
    if(isset($d['_user_preferences'])){
      $stats_p++;
      $ed=0;
      foreach ($d['_user_preferences'] as $key=>$value){
        if(strpos($key, "editor_ui")){
          if(strpos($key, "ataloguing")){
          $ed=1;
          $data[$Row["user_id"]][$key]=$value;
          aDa($key, $value);
          $keys[$key]=1;
          //echo "<strong>".$key."</strong>"."\n";
          //var_export($value);
        }}


      }
      $stats_pr+=$ed;

    }
    //echo "</pre>";

  }
echo "<hr>";
echo "záznamů ".$stats_u. ", z toho s nastaveníma ".$stats_p. " a s nastavením editoru ".$stats_pr;
echo "<hr>";
echo "<table><tr><td>stats</td>";
foreach ($keys as $key=>$numerouno){
  echo "<td>".$key."</td>";
}
echo "</tr>";


foreach ($data as $user => $info){
  echo "<tr><td>".$user. " ". $users[$user]. "</td>";
  foreach ($keys as $key=>$numerouno){
      if(isset($info[$key])){
        echo "<td><pre>";
        var_export($info[$key]);
        echo "</pre></td>";
      }else{
        echo "<td>&nbsp;</td>";
      }

  }
  echo "</tr>";

}
echo "</table><pre>";


var_export($analyze);
echo "</pre><hr><ul>";


foreach ($summary as $key=>$value){
  echo "<li> ".$key." ".$labely[$key]." -".$value."</li>";

}


?>
</ul>
