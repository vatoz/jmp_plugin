<style type="text/css">
    @media print {
        .no-print, .no-print * {
            display: none !important;
        }        
        #topNavContainer,#footerContainer,#leftNav{display:none;}
        #mainContent{border-right:none;  padding: 0 0 0 0;  margin-left:0px;  margin-top:0px;  width:100%;}
        #main{width:100%;  padding: 0 0 0 0;   margin-left:0px; } 
        *{
            font-size: 16px;
             font-family: "Times New Roman", Times, serif;
        }  
        
        
    }    

    table{
      padding:0px 0px 0px 0px;
      width:100%;
    }
    
    
</style>
<span class=noprint>Vyberte číselník</br></span>
<?php

//include "setup.php";

use phpDocumentor\Reflection\Types\Parent_;

$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}


  $variant=24;
  if(isset($_REQUEST['variant'])){
    $variant = intval($_REQUEST['variant']);
  }

  $title= "";
  $SQL="SELECT cl.list_id,cl.list_code, ca.name
  FROM `ca_lists` cl inner join ca_list_labels ca on cl.list_id = ca.list_id where locale_id=1 and deleted=0 
  and ca.list_id in (29,24,66)
  order by name";
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    if($Row['list_id']<>$variant){
      echo '<A class=noprintname href="?variant='.$Row['list_id'].'">'.$Row['name']."</a> ";
    }else{
      echo '<span class=noprint>'.$Row['name']."</span> ";
      $title = '<h1>'.$Row['name']."</h1>";
    }
  }


  echo $title;







$SQL_count="
select item_id, count(*) as cnt from (select item_id from ca_collections_x_vocabulary_terms 
UNION ALL 
select item_id from ca_entities_x_vocabulary_terms 
UNION ALL 
select item_id from ca_objects_x_vocabulary_terms 
UNION ALL 
select item_id from ca_loans_x_vocabulary_terms 
UNION ALL 
select item_id from ca_places_x_vocabulary_terms
UNION ALL 
select item_id from ca_movements_x_vocabulary_terms
UNION ALL 
select item_id from ca_object_lots_x_vocabulary_terms
UNION ALL 
select item_id from ca_object_representations_x_vocabulary_terms
UNION ALL 
select item_id from ca_occurrences_x_vocabulary_terms
UNION ALL 
select item_id from 	ca_representation_annotations_x_vocabulary_terms
UNION ALL 
select item_id from ca_storage_locations_x_vocabulary_terms
UNION ALL 
select item_id from ca_tour_stops_x_vocabulary_terms
UNION ALL 
select item_id from 	ca_user_representation_annotations_x_vocabulary_terms
UNION ALL
SELECT item_id FROM `ca_attribute_values` WHERE `element_id` in(226,14,231,238)

) as unt group by item_id



";

global $counts;
$counts=array();
$dotazCount = $pdo->query($SQL_count);

foreach($dotazCount  as $Row){
  $counts[$Row['item_id']]=$Row['cnt'];  
}

function renderCounts($i){
  global $counts;
  if(isset($counts[$i])){
    return "<small> (".$counts[$i].")</small>";

  }else{
    return "<small> (0)</small>";
  }
}

function renderElement($i,&$data,&$siblings){

  if (!isset($siblings[$i])){
    return $data[$i]['name_singular'] . renderCounts($i);
  }

  $value="\n<table border=1><tr><td ";

  if (isset($siblings[$i]) ) {
    $value.= " rowspan=". count($siblings[$i]);
  }

  $value.=">\n";
  if(isset($data[$i])){
    $value.= "<span>".$data[$i]['name_singular']. renderCounts($i)."</span>";
  }else{
    $value.= "&nbsp;";
  }
  $value.="</td>\n";

  if (isset($siblings[$i])){
    $render_flat=true;
    $names=array();
    foreach($siblings[$i] as $RowId){
      
      if(isset($siblings[$RowId])){
        $render_flat=false;
        //continue;
      }
      $names[] = "<span> ".$data[$RowId]['name_singular']. renderCounts($RowId)."</span>";
    }

    if($render_flat){
      $value.="<td>".implode("; ",$names)."</td>\n";
    }else{

    $cnt=0;
    foreach($siblings[$i] as $RowId){
      
        $value.="<td>\n".renderElement($RowId,$data,$siblings).
        "\n</td>";
        $cnt++;
        if($cnt< count($siblings[$i])){
          $value.="\n </tr> \n <tr> \n ";
        }
    }
    }
  }else{
    $value.="<td></td>";
  }
  $value.="\n </tr>\n</table>";

return $value;




}




$has_siblings=array();
$data=array();
$siblings=array();

$SQL="SELECT ca_list_items.*,name_singular FROM `ca_list_items` 
left join `ca_list_item_labels` on  ca_list_items.item_id = ca_list_item_labels.item_id 
WHERE `list_id` = '".$variant."' and (`locale_id` = '1' or parent_id is null)";
$dotaz = $pdo->query($SQL);
$main=0;
foreach($dotaz  as $Row){
  $data[$Row['item_id']]=$Row;  
  if($Row['parent_id']){
    $siblings[$Row['parent_id']][]=$Row['item_id'];  
  }else{
    $main=$Row['item_id'];
    $data[$Row['item_id']]['name_singular']="";
  }
}
//echo $main;
//echo $SQL;

echo renderElement($main,$data,$siblings);



?>
<span class=noprint>
<br><br><br><br></class>

