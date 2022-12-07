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

 	class VictimController extends ActionController {
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


         
     
    function Search(){
    //  $arr=get_defined_vars();
    //  $this->view->setVar('vars',$arr );
      
 	$this->render('victim.php');
    }

    }