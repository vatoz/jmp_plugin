Map test
<br>
<link rel="stylesheet" href="/app/plugins/jmp/css/leaflet.css"  />
<script src="/app/plugins/jmp/js/leaflet.js" ></script>

<div id="map" style="height: 400px; "></div>
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

//synagogue=2286

$Query="SELECT ca_attributes.row_id,value_decimal1,value_decimal2  FROM `ca_attributes`
left join ca_attribute_values on ca_attributes.attribute_id= ca_attribute_values.attribute_id 
WHERE ca_attributes.`element_id` = 102 AND `table_num` = 72 and row_id in 
(SELECT place_id
FROM `ca_places`
WHERE `type_id` in( 2286) AND `hierarchy_id` = 162) ";

//  $Query="SELECT *  FROM `ca_places` inner join ca_place_labels on ca_places.place_id= ca_place_labels.place_id   WHERE `hierarchy_id` = '162' AND `type_id` = '2286' AND `deleted` = '0'    and ca_place_labels.locale_id=1 ";
    
  $dotaz = $pdo->query($Query);
  foreach($dotaz  as $Row){


    echo "\n";
    echo "L.marker([".$Row["value_decimal1"].",".$Row["value_decimal2"]."]).addTo(map);";

}



?></script>