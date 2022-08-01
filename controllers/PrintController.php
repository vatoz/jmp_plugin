<?php
/* ----------------------------------------------------------------------
 * plugins/jmp/controllers/PrintController.php :
 
 * ----------------------------------------------------------------------
 */

 	require_once(__CA_LIB_DIR__.'/Configuration.php');
  require_once(__CA_MODELS_DIR__.'/ca_objects.php');
  require_once(__CA_MODELS_DIR__.'/ca_collections.php');
  require_once(__CA_MODELS_DIR__.'/ca_list_items.php');
 	//include_once(__CA_LIB_DIR__."/Vimeo/vimeo.php");

 	class PrintController extends ActionController {
 		# -------------------------------------------------------
 		protected $opo_config;		// plugin configuration file
 		# -------------------------------------------------------
 		#
 		# -------------------------------------------------------
 		public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
 			parent::__construct($po_request, $po_response, $pa_view_paths);
 			//$this->opo_config = Configuration::load(__CA_APP_DIR__.'/conf/search.conf');
      // Load plugin stylesheet
 			//MetaTagManager::addLink('stylesheet', __CA_URL_ROOT__."/app/plugins/jmp/css/plugin.css",'text/css');	
 		}
 		# -------------------------------------------------------
 		 	
      
      
      
      
    /*
    *Převedená tisková sestava NUX
    *
    *
    */
    function SbirkovaSestava(){
      return $this->pSbirkovaSestava("tiskovasestava.php");
    }
    function ObrazkovaSestava(){
      return $this->pSbirkovaSestava("obrazky.php");
      
    }
    
    private function pSbirkovaSestava($verze){
      if (!$this->request->user->canDoAction('jmp_tiskovasestava')) {
        #TODO  přidat odchod při nenastaveném právu
        //TODO $this->response->setRedirect($this->request->config->get('error_display_url').'/n/3000?r='.urlencode($this->request->getFullUrlPath()));
        //TODO  return;
      }
      
 			
      $objectId = $this->request->getParameter('object_id', pInteger);
      if($objectId==0){
        $objectId=184448;//TODO radši failovat
      }
      
      $t_obj=new ca_objects();
      $t_obj->setMode(ACCESS_READ);  //b
      $t_obj->load(array('object_id'=>$objectId));
      //$names=$t_obj->get("ca_objects.preferred_labels.displayname", array("returnAllLocales"=>true,"returnAsArray"=>true));
      
      $data = array(
          'idno' => array(    ),
          'ca_objects.id_numbers' => array(
              'returnWithStructure'=>true,               
          ),
          'ca_objects.type_id' => array(
              'convertCodesToDisplayText' => true,
              'locale' => 'cs_CZ'
          ),
          'ca_collections' => array(
              //'delimiter' => '|<->|',
              //'returnAllLocales' => true
               'returnWithStructure'=>true,               
          ),
          'ca_objects.preferred_labels' => array(
              'returnAllLocales'=>true, //?????
               'returnWithStructure'=>true, 
          
          ),
          'ca_objects.nonpreferred_labels' => array(
                 'returnWithStructure'=>true, 
                   'returnAllLocales'=>true, //?????
          ),
                    
          'ca_places' => array(
              'returnAllLocales'=>true, //?????
              'returnWithStructure'=>true,
              //'locale_id' => 1
          ),
          'ca_object_lots' => array(
              'locale' => 'cs_CZ'
          ),
          'ca_object_lots.acquisition_place_remark' => array(
              'locale' => 'cs_CZ'
          ),
          'ca_entities' => array(
              'returnWithStructure'=>true, 
              'locale' => 'cs_CZ'
          ),
          'ca_objects.creation_date_remark' => array(
              'locale' => 'cs_CZ'
          ),
          'ca_objects.material' => array(
      //        'returnAsArray' => true,
      //        'convertCodesToDisplayText' => true,
              'locale' => 'cs_CZ'
          ),
          'ca_objects.physical_condition' => array(
              'returnWithStructure'=>true, 
              'convertCodesToDisplayText' => false,
              'locale' => 'cs_CZ'
          ),
          'ca_objects.production_technique' => array(
      //        'returnAsArray' => true,
      //        'convertCodesToDisplayText' => true,
              'locale' => 'cs_CZ'
          ),
          'ca_objects.dimensions' => array(
              'returnWithStructure'=>true, 
              'locale' => 'cs_CZ'
          ),
          'ca_objects.remark' => array(
              'locale' => 'cs_CZ',
              'returnWithStructure'=>true
          ),
          'ca_objects.description' => array(
              'locale' => 'cs_CZ'
          ),
          'ca_objects.signs_on_object' => array(
              'returnWithStructure'=>true, 
//              'convertCodesToDisplayText' => true,
          ),
      //    'ca_objects.signs_on_object.signs_translation' => array(
      //        'returnAsArray' => true,
      //        'convertCodesToDisplayText' => true,
      //    ),
      //    'ca_objects.signs_on_object.signs_inscription' => array(
      //        'returnAsArray' => true,
      //        'convertCodesToDisplayText' => true,
      //    ),
      //    'ca_objects.signs_on_object.signs_language_1' => array(
      //        'returnAsArray' => true,
      //    ),
          'ca_list_items.preferred_labels' => array(//todo
              'convertCodesToDisplayText' => true,
              'locale' => 'cs_CZ'
          ),
          'ca_list_items' => array(//todo
              'returnWithStructure'=>true,               'locale' => 'cs_CZ'
          ),
          'ca_locales' => array(
              'returnWithStructure'=>true, 
          ),
          'ca_object_representations'=>array(
              'returnWithStructure'=>true, 
          ),
          'ca_object_representations.media.medium'=>array(
              'returnWithStructure'=>true, 
          ),
          
      );

      $result=array();
      foreach($data as $field=>$params){
              $result[$field]=$t_obj->get($field, $params);        
      }
      
      $original_result=$result;
      
      //var_export($result['ca_collections']);
      // klíče lokalizací
      if (!defined('LANG_CS')) define('LANG_CS', 1);
      if (!defined('LANG_EN')) define('LANG_EN', 2);

      
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // identifikační čísla

      $inventarniCislo = '';
      $prirustkoveCislo = '';
      $jineCislo = '';
      $signatura = '';
      foreach ($result['ca_objects.id_numbers'][$objectId] as $number) {
          
          if ($number['id_type'] == '44') {
              if (is_numeric($number['id']) && strlen($number['id']) == 6) {
                  $inventarniCislo = substr($number['id'], 0, 3) . '.' . substr($number['id'], 3, 3);
              } else {
                  $inventarniCislo = $number['id'];
              }
              if (isset($number['id_suffix'])) $inventarniCislo .= '/' . $number['id_suffix'];
          } else if ($number['id_type'] == '45') {
              $prirustkoveCislo = $number['id'];
              if (isset($number['id_suffix'])) $prirustkoveCislo .= '/' . $number['id_suffix'];
          } else if ($number['id_type'] == '2429') {
              $jineCislo = $number['id'];
              if (isset($number['id_suffix'])) $jineCislo .= '/' . $number['id_suffix'];
          } else if ($number['id_type'] == '43') {
              $signatura = $number['id'];
              if (isset($number['id_suffix'])) $signatura .= '/' . $number['id_suffix'];
          }
      }
      
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // místo vzniku

      
      if (!defined('REL_CREATED')) define('REL_CREATED', 'created');
      if (!defined('REL_CREATED_UNSURE')) define('REL_CREATED_UNSURE', 'created_unsure');

      $placesCreated = [];
      $unsure = false;
      foreach ($result['ca_places'] as $place) {
          //var_export($place);
          // jazyk
          if (isset($place['labels'][LANG_CS])) {    // cs
              $label = $place['labels'][LANG_CS];
          } else if (isset($place['labels'][LANG_EN])) {    // en
              $label = $place['labels'][LANG_EN];
          } else {    // jakýkoli jiný jazyk
            //var_export($place);
            $label="hodnota je divná";
              $label = reset($place['labels']);
          }

          // typ vztahu
          if ($place['relationship_type_code'] === REL_CREATED) {
              $placesCreated = [$label];
              $unsure = false;
          } elseif ($place['relationship_type_code'] === REL_CREATED_UNSURE) {
              $placesCreated[] = $label;
              $unsure = true;
          }
      }

      if (count($placesCreated) === 1 && $unsure) {
          $placesCreated[0] = $placesCreated[0] . ' (?)';
      }

      $mistoVzniku = implode(' nebo ', $placesCreated);

      
        

      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // autor

      /*
      [dané jméno ][příjmení][ (rok narození-rok úmrtí)]

      dané jméno = ca_entities.preferred_labels.forename

      příjmení = ca_entities.preferred_labels.surname

      rok narození = ca_entities.entity_iti_events_date; typ události: birth (item_id: 1100); omezeno pouze na rok

      rok úmrtí =  ca_entities.entity_iti_events.entity_iti_events_date; typ události: death_holocaust (item_id: 1101)

      NEBO v případě, že není vyplněno
      ca_entities.entity_iti_events.entity_iti_events_date; typ události: fate_death (item_id: 1067)

      NEBO v případě, že není vyplněno
      ca_entities.entity_iti_events.entity_iti_events_date; typ události: date_death_outside_holocaust (item_id: 1102)

      NEBO v případě, že není vyplněno (to je ta problematičtější část)
      datum posledního (obvykle druhého) transportu - occ_transport, occurrence_id: 68
      */

      $autor = '';
      foreach ($result['ca_entities'] as $place) {
        //var_export($place);
          if ($place['relationship_type_code'] == 'creator') {
              $autor = ''
                  . (!empty($place['forename']) ? $place['forename'] . ' ' : '')
                  . (!empty($place['surname']) ? $place['surname'] . ' ' : '')
                  . '';
          }
      }

  

      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // stav

      if (isset($result['ca_objects.physical_condition'][$objectId])) {
          $stav = array_pop($result['ca_objects.physical_condition'][$objectId]);

          if (isset($stav['condition_intervention'])) {
              if ($stav['condition_intervention'] == '2235') {
                  $stav['condition_intervention'] = 'bez zásahu';
              } else if ($stav['condition_intervention'] == '2233') {
                  $stav['condition_intervention'] = 'restaurováno';
              } else if ($stav['condition_intervention'] == '2234') {
                  $stav['condition_intervention'] = 'konzervováno';
              }
          } else {
              $stav['condition_intervention'] = '';
          }

          if ($stav['condition'] == '122') {
              $stav['condition'] = '1) bezvadný, lehce opotřebený';
          } else if ($stav['condition'] == '123') {
              $stav['condition'] = '2) z části opotřebený';
          } else if ($stav['condition'] == '124') {
              $stav['condition'] = '3) silně poškozený';
          } else if ($stav['condition'] == '125') {
              $stav['condition'] = '4) v havarijním stavu';
          } else if ($stav['condition'] == '126') {
              $stav['condition'] = '5) nenávratně poškozený, zničený';
          }
      } else {
          $stav = array('condition_intervention' => '', 'condition' => '');
      }


      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // rozměry

      $delka = array();
      $hloubka = array();
      $jiny = array();
      $polomer = array();
      $prumer = array();
      $vyska = array();
      $sirka = array();
      if (isset($result['ca_objects.dimensions'][$objectId])) {
          foreach ($result['ca_objects.dimensions'][$objectId] as $dimensions) {
              switch ($dimensions['length_type']) {
                  case '35':
                      $delka[] = str_replace(' mm', '', $dimensions['length']);
                      break;
                  case '2055':
                      $hloubka[] = str_replace(' mm', '', $dimensions['length']);
                      break;
                  case '38':
                      $jiny[] = str_replace(' mm', '', $dimensions['length']);
                      break;
                  case '37':
                      $polomer[] = str_replace(' mm', '', $dimensions['length']);
                      break;
                  case '36':
                      $prumer[] = str_replace(' mm', '', $dimensions['length']);
                      break;
                  case '33':
                      $vyska[] = str_replace(' mm', '', $dimensions['length']);
                      break;
                  case '34':
                      $sirka[] = str_replace(' mm', '', $dimensions['length']);
                      break;

              }
          }
      }


      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // kolekce
      
    
      
      $collections = [];
      foreach($result['ca_collections'] as $collection){
          $col_rev[$collection['collection_id']]=$collection['labels'][1];
          $collections=array_merge ($collections , $this->retrieveCollectionHierarchy($collection['collection_id']));                  
      }
      
      
    
      
      
      if(isset($result['ca_objects.remark'][$objectId])){
        $t="";
        foreach($result['ca_objects.remark'][$objectId]  as $remark){
            if($t!=="")$t.="<br><hr>";
            $t.=$remark['remark'];
        }
        $result['ca_objects.remark']=$t;
      }
      
      
      
      
      
      
      /*
      
      
      $collections = [];
      $collectionHierarchy = array_shift($result['ca_collections.hierarchy']);
      if (count($collectionHierarchy) > 0) {
          $keyId = 0;
          foreach ($collectionHierarchy as $row) {
              if (isset($row[LANG_CS])) {
                  $collections[$keyId] = array_shift($row[LANG_CS]);
              } elseif (isset($row[LANG_EN])) {
                  $collections[$keyId] = array_shift($row[LANG_EN]);
              }
              $keyId++;
          }
      }
*/

      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // materiál

      //$materials = [];
      //if (isset($result['ca_objects.material'])) {
      //    foreach ($result['ca_objects.material'] as $material) {
      //        if (isset($material['material'])) {
      //            $materials[] = $material['material'];
      //        }
      //    }
      //}

      $materials = $this->translate($result['ca_objects.material']);


      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // technika

      //$techniques = [];
      //if (isset($result['ca_objects.production_technique'])) {
      //    foreach ($result['ca_objects.production_technique'] as $technique) {
      //        if (isset($technique['production_technique'])) {
      //            $techniques[] = $technique['production_technique'];
      //        }
      //    }
      //}

      $techniques = $this->translate($result['ca_objects.production_technique']);

      if (!is_array ($result['ca_objects.signs_on_object'])) $result['ca_objects.signs_on_object']=array();

      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // nápisy
      //var_export($result['ca_objects.signs_on_object']);
      //if (!defined('LANGUAGE_HEBREW')) define('LANGUAGE_HEBREW', 'hebrejsky');

      $inscriptions = [];
      
      foreach ($result['ca_objects.signs_on_object'] as  $item) {
        foreach ($item as $row){
          $value="";$translation="";$bdo="ltr";
          if(isset($row['signs_inscription'])) $value= $row['signs_inscription'];
          if(isset($row['signs_translation'])) $translation= $row['signs_translation'];
          if(isset($row['signs_language_1'])){
            if($row['signs_language_1']==3850){
              $bdo=rtl;
            }
          }              
          
          if (!empty($translation) || !empty($value)) {
              $inscription = new stdClass();
              $inscription->value = nl2br($value);
              $inscription->translation = nl2br($translation);
              $inscription->bdo = $bdo;

              $index = count($inscriptions) + 1;
              $inscriptions[$index] = $inscription;
          }
          
        }        
      }
      
      $preferovany="";$nepreferovany="";
      if(isset($result['ca_objects.preferred_labels'][$objectId])){
        if(isset($result['ca_objects.preferred_labels'][$objectId][1])){
          $candidate=          $result['ca_objects.preferred_labels'][$objectId][1];
        }else{
          $candidate=array_pop( $result['ca_objects.preferred_labels'][$objectId]);
        }
        $candidate=array_pop($candidate);
        $preferovany=$candidate["name"] ;        
      }
      
      if(isset($result['ca_objects.nonpreferred_labels'][$objectId])){
        if(isset($result['ca_objects.nonpreferred_labels'][$objectId][1])){
          $candidate=          $result['ca_objects.nonpreferred_labels'][$objectId][1];
        }else{
          $candidate=array_pop( $result['ca_objects.nonpreferred_labels'][$objectId]);
        }
        $candidate=array_pop($candidate);
        $nepreferovany=$candidate["name"]    ;     
      }
      
      
      
      
      
      
      
      
      
      if(isset($n[$Id])){
        if(isset($n[$Id][1])){
          $t= array_pop($n[$Id][1]);
          $label= $t["name"];
          
        }
        
      }
    
      
    
      
      
      
      
      

      unset($t_obj);

      /**Vezmu všechny lokální proměnné a zabalím je do pole**/
      $arr=get_defined_vars();
      $this->view->setVar('vars',$arr );
      
 			$this->render($verze);
 		}
    
    function About(){
      $this->render('about.php');
    }

 		   
     function loadListItem($Id){
        $t_li=new ca_list_items();
        $t_li->load(array("item_id"=>$Id));
        $n=$t_li->get("preferred_labels",array("returnAllLocales"=>1, "returnWithStructure"=>true));
        if(isset($n[$Id][1])){
            $t=array_pop($n[$Id][1]);
            return $t["name_singular"];          
        }
        if(isset($n[$Id])){
          $t=array_pop($n[$Id]);
          $t=array_pop($t);
          return $t["name_singular"];
        }
        return "Chyba, list item ".$Id." nemá v databázi jméno";
        
        
     }
     
     
     function translate($metadataInput) {
        /** @var $metadata array requirováno v souboru @file metadata.php */
        $result = [];

        if (is_string($metadataInput)) {
            
            $keys = explode(';', str_replace(",",";",$metadataInput));
            if (count($keys) > 0) {
                foreach ($keys as $key) {
                    $result[] = $this->loadListItem(trim($key));
                }
            }
        }
        return $result;
    }

    function retrieveCollectionHierarchy($Id){
      $t_col=new ca_collections();
      $t_col->load(array("collection_id"=>$Id));
      
      //$u= $t_col->hierarchyWithTemplate('<item>^ca_collections.idno  ^ca_collections.preferred_labels </item> ',array("returnAllLocales"=>true));
      //var_export($u);
      //return $u;
    
      $g=$t_col->get("parent_id");
      $n=$t_col->get("preferred_labels",array("returnAllLocales"=>1, "returnWithStructure"=>true));
      $label="Unknown??? BUG IN APP";
      if(isset($n[$Id])){
        if(isset($n[$Id][1])){
          $t= array_pop($n[$Id][1]);
          $label= $t["name"];
          
        }
        
      }
      
      //var_export($n);
      
      $result= array($label);
      if($g>0){
        $parent = $this->retrieveCollectionHierarchy($g);                
        $result=array_merge($parent,$result);        
      }
      return $result;
      
      
      
      
      
    }    
    
     

    
    
 	}
 


