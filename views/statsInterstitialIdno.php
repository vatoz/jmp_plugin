<?php
echo "Vypíše idno záznamu, u kterého jsou interstitial fields ";
echo "<textarea cols=80 rows=100>";
//include "setup.php";

$tables=array( 14,15,21,22,23,24,26,30,34,52,53,54,55,58,59,61,62,63,64,65,68,69,70,73,74,75,76,85,86,87);
$tablesdatamodel=array(
  "ca_acl" 									=> 1,
	"ca_application_vars"						=> 2,
	"ca_attribute_values"						=> 3,
	"ca_attributes"								=> 4,
	"ca_change_log"								=> 5,
	"ca_change_log_subjects"					=> 6,
	"ca_representation_annotations_x_entities"	=> 7,
	"ca_representation_annotations_x_objects"	=> 8,
	"ca_representation_annotations_x_occurrences"		=> 9,
	"ca_representation_annotations_x_places"			=> 10,
	"ca_representation_annotations_x_vocabulary_terms"	=> 11,
	"ca_collection_labels"						=> 12,
	"ca_collections"							=> 13,
	"ca_collections_x_collections"				=> 14,
	"ca_collections_x_vocabulary_terms"			=> 15,
	"ca_search_form_type_restrictions" 			=> 16,
	"ca_data_import_events"						=> 17,
	"ca_data_import_items"						=> 18,
	"ca_entities"								=> 20,
	"ca_entities_x_collections"					=> 21,
	"ca_entities_x_occurrences"					=> 22,
	"ca_entities_x_places"						=> 23,
	"ca_entities_x_vocabulary_terms"			=> 24,
	"ca_entity_labels"							=> 25,
	"ca_entities_x_entities"					=> 26,
	"ca_groups_x_roles"							=> 30,
	"ca_ips"									=> 31,
	"ca_list_item_labels"						=> 32,
	"ca_list_items"								=> 33,
	"ca_list_items_x_list_items"				=> 34,
	"ca_list_labels"							=> 35,
	"ca_lists"									=> 36,
	"ca_locales"								=> 37,
	"ca_object_lot_labels"						=> 40,
	"ca_metadata_element_labels"				=> 41,
	"ca_metadata_elements"						=> 42,
	"ca_metadata_type_restrictions"				=> 43,
	"ca_multipart_idno_sequences"				=> 44,
	"ca_object_labels"							=> 50,
	"ca_object_lots"							=> 51,
	"ca_object_lots_x_collections"				=> 52,
	"ca_object_lots_x_entities"					=> 53,
	"ca_object_lots_x_occurrences"				=> 54,
	"ca_object_lots_x_places"					=> 55,
	"ca_object_representations"					=> 56,
	"ca_objects"								=> 57,
	"ca_objects_x_collections"					=> 58,
	"ca_objects_x_entities"						=> 59,
	"ca_objects_x_object_representations"		=> 61,
	"ca_objects_x_objects"						=> 62,
	"ca_objects_x_occurrences"					=> 63,
	"ca_objects_x_places"						=> 64,
	"ca_objects_x_vocabulary_terms"				=> 65,
	"ca_occurrence_labels"						=> 66,
	"ca_occurrences"							=> 67,
	"ca_occurrences_x_collections"				=> 68,
	"ca_occurrences_x_occurrences"				=> 69,
	"ca_occurrences_x_vocabulary_terms"			=> 70,
	"ca_place_labels"							=> 71,
	"ca_places"									=> 72,
	"ca_places_x_collections"					=> 73,
	"ca_places_x_occurrences"					=> 74,
	"ca_places_x_places"						=> 75,
	"ca_places_x_vocabulary_terms"				=> 76,
	"ca_relationship_relationships"				=> 77,
	"ca_relationship_type_labels"				=> 78,
	"ca_relationship_types"						=> 79,
	"ca_representation_annotations"				=> 82,
	"ca_representation_annotation_labels"		=> 83,
	"ca_object_representations_x_entities"				=> 85,
	"ca_object_representations_x_occurrences"			=> 86,
	"ca_object_representations_x_places"				=> 87,
	"ca_storage_location_labels"				=> 88,
	"ca_storage_locations"						=> 89,
	"ca_task_queue"								=> 90,
	"ca_user_groups"							=> 91,
	"ca_user_roles"								=> 92,
	"ca_users"									=> 94,
	"ca_users_x_groups"							=> 95,
	"ca_users_x_roles"							=> 96,
	"ca_editor_ui_bundle_placements"			=> 97,
	"ca_editor_ui_labels"						=> 98,
	"ca_editor_ui_screen_labels"				=> 99,
	"ca_editor_ui_screens"						=> 100,
	"ca_editor_uis"								=> 101,
	"ca_editor_uis_x_user_groups"				=> 102,
	"ca_sets"									=> 103,
	"ca_set_labels"								=> 104,
	"ca_set_items"								=> 105,
	"ca_set_item_labels"						=> 106,
	"ca_sets_x_user_groups"						=> 107,
	"ca_object_representation_labels"			=> 108,
	"ca_item_comments"							=> 109,
	"ca_item_tags"								=> 110,
	"ca_items_x_tags"							=> 111,
	"ca_editor_uis_x_roles"						=> 113,
	"ca_editor_ui_screens_x_roles"				=> 114,
	"ca_object_lots_x_vocabulary_terms"			=> 115,
	"ca_object_representations_x_vocabulary_terms"		=> 116,
	"ca_objects_x_storage_locations"			=> 119,
	"ca_object_lots_x_storage_locations"		=> 120,
	"ca_search_forms"							=> 121,
	"ca_search_form_labels"						=> 122,
	"ca_object_representation_multifiles"		=> 123,
	"ca_bundle_displays"						=> 124,
	"ca_bundle_display_placements"				=> 125,
	"ca_bundle_display_labels"					=> 126,
	"ca_bundle_displays_x_user_groups"			=> 127,
	"ca_editor_ui_screen_type_restrictions"		=> 131,
	"ca_collections_x_storage_locations"		=> 132,
	"ca_loans"									=> 133,
	"ca_loan_labels"							=> 134,
	"ca_loans_x_objects"						=> 135,
	"ca_loans_x_entities"						=> 136,
	"ca_movements"								=> 137,
	"ca_movement_labels"						=> 138,
	"ca_movements_x_objects"					=> 139,
	"ca_movements_x_object_lots"				=> 140,
	"ca_movements_x_entities"					=> 141,
	"ca_loans_x_movements"						=> 143,
	"ca_watch_list"								=> 144,
	"ca_user_notes"								=> 145,
	"ca_data_import_event_log"          => 146,
	"ca_search_forms_x_user_groups"	 => 147,
	"ca_search_forms_x_users"	 => 148,
	"ca_bundle_displays_x_users"	 => 149,
	"ca_sets_x_users"	 => 150,
	"ca_editor_uis_x_users"	 => 151,
	"ca_search_form_placements" => 152,
	"ca_tours" => 153,
	"ca_tour_labels" => 154,
	"ca_tour_stops" => 155,
	"ca_tour_stop_labels" => 156,
	"ca_tour_stops_x_objects"	 => 157,
	"ca_tour_stops_x_entities"	 => 158,
	"ca_tour_stops_x_places"	 => 159,
	"ca_tour_stops_x_occurrences"	 => 160,
	"ca_tour_stops_x_collections"	 => 161,
	"ca_tour_stops_x_vocabulary_terms"	 => 162,
	"ca_tour_stops_x_tour_stops"	 => 163,
	"ca_bookmark_folders" => 164,
	"ca_bookmarks" => 165,
	"ca_object_representations_x_collections" => 172,
	"ca_object_representations_x_storage_locations" => 173,
	"ca_object_representations_x_object_representations" => 174,
	"ca_entities_x_storage_locations" => 177,
	"ca_loans_x_collections" => 178,
	"ca_loans_x_object_lots" => 179,
	"ca_loans_x_occurrences" => 180,
	"ca_loans_x_places" => 181,
	"ca_loans_x_storage_locations" => 182,
	"ca_loans_x_vocabulary_terms" => 183,
	"ca_loans_x_loans" => 184,
	"ca_movements_x_collections" => 185,
	"ca_movements_x_occurrences" => 186,
	"ca_movements_x_places" => 187,
	"ca_movements_x_storage_locations" => 188,
	"ca_movements_x_vocabulary_terms" => 189,
	"ca_movements_x_movements" => 190,
	"ca_places_x_storage_locations" => 191,
	"ca_occurrences_x_storage_locations" => 192,
	"ca_storage_locations_x_vocabulary_terms" => 193,
	"ca_storage_locations_x_storage_locations" => 194,
	"ca_editor_ui_type_restrictions" => 198,
	"ca_object_lots_x_object_representations" => 199,
	"ca_loans_x_object_representations" => 200,
	"ca_movements_x_object_representations" => 201,
	"ca_data_importers" => 202,
	"ca_data_importer_labels" => 203,
	"ca_data_importer_groups" => 204,
	"ca_data_importer_items" => 205,
	"ca_data_importer_log" => 206,
	"ca_data_importer_log_items" => 207,
	"ca_data_exporters" => 208,
	"ca_data_exporter_labels" => 209,
	"ca_data_exporter_items" => 210,
	"ca_object_lots_x_object_lots" => 211,
	"ca_bundle_display_type_restrictions" => 212,
	"ca_object_representation_captions" => 213,
	"ca_metadata_dictionary_entries" => 214,
	"ca_metadata_dictionary_rules" => 215,
	"ca_attribute_value_multifiles" => 216,
	"ca_metadata_dictionary_rule_violations" => 217,
	"ca_object_checkouts" => 218,
	"ca_user_representation_annotations" => 219,
	"ca_user_representation_annotation_labels" => 220,
	"ca_user_representation_annotations_x_entities"	=> 221,
	"ca_user_representation_annotations_x_objects"	=> 222,
	"ca_user_representation_annotations_x_occurrences"		=> 223,
	"ca_user_representation_annotations_x_places"			=> 224,
	"ca_user_representation_annotations_x_vocabulary_terms"	=> 225,
	"ca_search_indexing_queue" => 226,
	"ca_editor_ui_screens_x_user_groups" => 227,
	"ca_editor_ui_screens_x_users" => 228,
	"ca_guids" => 229,
	"ca_replication_log" => 230,
	"ca_user_sorts" => 231,
	"ca_user_sort_items" => 232,
	"ca_notifications" => 233,
	"ca_notification_subjects" => 234,
	"ca_site_pages" => 235,
	"ca_site_templates" => 236,
	"ca_site_page_media" => 237,
	"ca_metadata_alert_rules" => 238,
	"ca_metadata_alert_rule_labels"	=> 239,
	"ca_metadata_alert_triggers" => 240,
	"ca_metadata_alert_rules_x_user_groups" => 241,
	"ca_metadata_alert_rules_x_users" => 242,
	"ca_metadata_alert_rule_type_restrictions" => 243

);


function getTableName($Id){
  global $tablesdatamodel;
  foreach($tablesdatamodel as $Key=>$Value){
    if($Value==$Id) return $Key;
  }
  die("no key ".$Id);
}

function getField($Id,$Left=true){
  global $pdo;
  $i=0;;
  $SQL= "show COLUMNS from ".getTableName($Id);
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    $i++;
    if($Left &&  $i==2) return  $Row["Field"];
    if(!$Left &&  $i==($Id==22?4:3)) return $Row["Field"];
  }
  die("divna tabulka");
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

echo "idno;attribute;via;side\n";

$data=array();

  $SQL= "SELECT * from ca_metadata_elements";

  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    $data[$Row["element_id"]] = $Row;
  }


foreach($data  as $key=>$value){
foreach($tables as $idtable ){
  $SQL= "SELECT row_id  from ca_attributes where table_num = ".$idtable ."  AND element_id= ".$key;
  $result=array() ;
  $dotaz = $pdo->query($SQL);
  foreach($dotaz  as $Row){
    $result[]=$Row["row_id"];
  }
  if(! count($result)) continue;

  $sides =array(false,true);
  foreach ($sides as $side){


    $SQL= "select ".getField($idtable,$side)." from ".getTableName($idtable). " where relation_id in (".implode(",",$result).")";
        $f=getField($idtable,$side);
    $result2=array();
    $dotaz = $pdo->query($SQL);
    foreach($dotaz  as $Row){
      $result2[]=$Row[$f];
    }


    $f=substr($f,0,-3);
        if(substr($f, -5)=="_left") $f=substr($f,0,-5);
    if(substr($f, -6)=="_right") $f=substr($f,0,-6);
    $SQL= "select idno from ca_";
    if($f=="entity"){
      $SQL.="entities ";
    }else{
      $SQL.=$f."s ";
    }

    $SQL.= "WHERE ".$f."_id in(". implode(", ",$result2).")";

    $dotaz = $pdo->query($SQL);
    foreach($dotaz  as $Row){
      echo $Row["idno"].";".$value["element_code"].";".getTableName($idtable).";".$side."\n";
    }






  }
}
}



echo "</textarea>";
