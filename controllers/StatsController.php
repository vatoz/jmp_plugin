<?php
/* ----------------------------------------------------------------------
 * plugins/jmp/controllers/StatsController.php :
 
 * ----------------------------------------------------------------------
 */

require_once(__CA_LIB_DIR__ . '/Configuration.php');
//require_once(__CA_MODELS_DIR__.'/ca_objects.php');
//require_once(__CA_MODELS_DIR__.'/ca_collections.php');
//require_once(__CA_MODELS_DIR__.'/ca_list_items.php');
//include_once(__CA_LIB_DIR__."/Vimeo/vimeo.php");

class StatsController extends ActionController
{
  # -------------------------------------------------------
  protected $opo_config;    // plugin configuration file
  # -------------------------------------------------------
  #
  # -------------------------------------------------------
  public function __construct(&$po_request, &$po_response, $pa_view_paths = null)
  {
    parent::__construct($po_request, $po_response, $pa_view_paths);
    //$this->opo_config = Configuration::load(__CA_APP_DIR__.'/conf/search.conf');
    // Load plugin stylesheet
  }
  # -------------------------------------------------------


  private function renderStat($i,$check_privs=true)
  {
    if($check_privs){
    if (!$this->request->user->canDoAction('jmp_stats')) {
      $this->response->setRedirect($this->request->config->get('error_display_url') . '/n/3000?r=' . urlencode($this->request->getFullUrlPath()));
      return;
    }
    }
    $this->render('stats' . $i . '.php');
  }

  function About()
  {
    return $this->renderStat('');
  }
  function DRPSObjects()
  {
    return $this->renderStat('DRPSObjects');
  }
 
  function ElementsRequired()
  {
    return $this->renderStat('ElementsRequired');
  }

  function EntitiesTSV()
  {
    return $this->renderStat('EntitiesTSV');
  }

  function Fonts()
  {
    return $this->renderStat('Fonts');
  }

  function IdnoSearchRebuild()
  {
    return $this->renderStat('IdnoSearchRebuild');
  }
  function IdnoRegexp()
  {
    return $this->renderStat('IdnoRegexp');
  }
  function IdnoSequences()
  {
    return $this->renderStat('IdnoSequences');
  }

  function ImageThumbTester()
  {
    return $this->renderStat('ImageThumbTester');
  }

  function ImageMultifileTester()
  {
    return $this->renderStat('ImageMultifileTester');
  }
 
  function ImagesImport()
  {
    return $this->renderStat('ImagesImport');
  }
 
  function InterstitialAttribute()
  {
    return $this->renderStat('InterstitialAttribute');
  }
  function InterstitialIdno()
  {
    return $this->renderStat('InterstitialIdno');
  }

  function ListItems()
  {
    return $this->renderStat('ListItems');
  
  }

  function ListItemsGraph()
  {
    return $this->renderStat('ListItemsGraph',false);
  
  }

  function ListItemsSex()
  {
    return $this->renderStat('ListItemsSex');
  }

  function ObjectsPrimaryImageHidden()
  {
    return $this->renderStat('ObjectsPrimaryImageHidden');
  }

  function PlacesMap()
  {
    return $this->renderStat('PlacesMap');
  }
  function PlacesCSV()
  {
    return $this->renderStat('PlacesCSV');
  }


  function RelationshipTypesCsv()
  {
    return $this->renderStat('RelationshipTypesCsv');
  }

  function UserCopy()
  {
    return $this->renderStat('UserCopy');
  }

  function UserEditors()
  {
    return $this->renderStat('UserEditors');
  }

  function UserPrivs()
  {
    return $this->renderStat('UserPrivs');
  }

   


 

}
