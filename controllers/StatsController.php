<?php
/* ----------------------------------------------------------------------
 * plugins/jmp/controllers/StatsController.php :
 
 * ----------------------------------------------------------------------
 */

 	require_once(__CA_LIB_DIR__.'/Configuration.php');
  //require_once(__CA_MODELS_DIR__.'/ca_objects.php');
  //require_once(__CA_MODELS_DIR__.'/ca_collections.php');
  //require_once(__CA_MODELS_DIR__.'/ca_list_items.php');
 	//include_once(__CA_LIB_DIR__."/Vimeo/vimeo.php");

 	class StatsController extends ActionController {
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
 		 	
    
    function About(){
      if (!$this->request->user->canDoAction('jmp_stats')) {
          $this->response->setRedirect($this->request->config->get('error_display_url').'/n/3000?r='.urlencode($this->request->getFullUrlPath()));
        return;
      }
      $this->render('stats.php');
    }
    
    private function renderStat($i){
      if (!$this->request->user->canDoAction('jmp_stats')) {
          $this->response->setRedirect($this->request->config->get('error_display_url').'/n/3000?r='.urlencode($this->request->getFullUrlPath()));
          return;
      }
      $this->render('stats'.$i.'.php');                                
    }
    
    function RelationshipTypesCsv(){    
      return $this->renderStat(1);                    
    }
    
    function InterstitialAttribute(){
      return $this->renderStat(2);               
    }
    function InterstitialIdno(){
      return $this->renderStat(3);  
    }
    function UserEditors(){
      return $this->renderStat(4);  
    }
    function ElementsRequired(){
      return $this->renderStat(5);  
    }
    
    function UserPrivs(){
      return $this->renderStat(6);  
    }
    
    
    function UserCopy(){
      return $this->renderStat(12);  
    }
    
    
    function ListItems(){
      return $this->renderStat(7);  
    }
    
    function ListItemsSex(){
      return $this->renderStat(9);  
    }
    
    function ImportImages(){
      return $this->renderStat(10);  
    }
    function Regexp(){
      return $this->renderStat(11);  
    }
 		   
    function ThumbTester(){
      return $this->renderStat(13);  
    }
    function MultifileTester(){
      return $this->renderStat(14);  
    }
    function DRPSObjects(){
      return $this->renderStat(15);  
    }
    function MultipartIdnoSequences(){
      return $this->renderStat(16);  
    }
 		
 	}
 