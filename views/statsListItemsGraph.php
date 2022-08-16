Klíčová slova

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



function renderElement($i,&$data,&$siblings){
  
  if (!isset($siblings[$i])){
    return $data[$i]['name_singular'];
  }

  $value="\n<table border=1><tr><td ";

  if (isset($siblings[$i]) ) {
    $value.= " rowspan=". count($siblings[$i]);
  }

  $value.=">\n";
  if(isset($data[$i])){
    $value.= "<span>".$data[$i]['name_singular']."</span>";
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
      $names[] = "<span> ".$data[$RowId]['name_singular']."</span>";
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
WHERE `list_id` = '24' and `locale_id` = '1'";
$dotaz = $pdo->query($SQL);

foreach($dotaz  as $Row){
  $data[$Row['item_id']]=$Row;  
  if($Row['parent_id']){
    $siblings[$Row['parent_id']][]=$Row['item_id'];  
  }
}


echo renderElement(106,$data,$siblings);



?>

