Map test
<br>
<link rel="stylesheet" href="/app/plugins/jmp/css/leaflet.css"  />
<script src="/app/plugins/jmp/js/leaflet.js" ></script>

<div id="map" style="height: 600px; "></div>
<script>
    var map = L.map('map').setView([49.992, 14.651], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
    }).addTo(map);
    
<?php


$dsn = 'mysql:dbname=' . __CA_DB_DATABASE__ . ';host=' .__CA_DB_HOST__ . '';
$user = __CA_DB_USER__;
$password = __CA_DB_PASSWORD__;


try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$maps=array();


$Q1="SELECT * FROM `ca_list_items` WHERE `parent_id` = '174'";
$dotaz1 = $pdo->query($Q1);
  foreach($dotaz1  as $Row1){

$list=array();

$Query="SELECT ca_attributes.row_id,value_decimal1,value_decimal2  FROM `ca_attributes`
left join ca_attribute_values on ca_attributes.attribute_id= ca_attribute_values.attribute_id 
WHERE ca_attributes.`element_id` = 102 AND `table_num` = 72 and row_id in 
(SELECT place_id
FROM `ca_places`
WHERE `type_id` = ".$Row1["item_id"]." AND `hierarchy_id` = 162 AND deleted=0) ";

//  $Query="SELECT *  FROM `ca_places` inner join ca_place_labels on ca_places.place_id= ca_place_labels.place_id   WHERE `hierarchy_id` = '162' AND `type_id` = '2286' AND `deleted` = '0'    and ca_place_labels.locale_id=1 ";


  $dotaz = $pdo->query($Query);
  foreach($dotaz  as $Row){
 
    $list[]= "L.marker([".$Row["value_decimal1"].",".$Row["value_decimal2"]."],{ win_url: '/index.php/editor/places/PlaceEditor/Edit/place_id/".$Row["row_id"] ."#'  }).on('click', onClick)";

}
if(count($list)){
    echo "\n var gr_".$Row1["item_id"]." = L.layerGroup([".implode(",\n",$list)."]);";
    $maps[]='"'.$Row1["idno"].'" : gr_'.$Row1["item_id"];
}

}

echo "\n var overlayMaps={".implode(",",$maps)."};";

?>
var layerControl = L.control.layers( overlayMaps).addTo(map);
function onClick(e) {
        //console.log(this.options.win_url);
        window.open(this.options.win_url);
    }
</script>