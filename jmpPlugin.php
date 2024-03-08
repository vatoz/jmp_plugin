<?php
/* ----------------------------------------------------------------------
 * jmpPlugin.php : Functions for Jewish museum in Prague
 * ----------------------------------------------------------------------
 *
 * ----------------------------------------------------------------------
 */

class jmpPlugin extends BaseApplicationPlugin {



		# -------------------------------------------------------
		public function __construct($ps_plugin_path) {
			$this->description = _t('Functions for Jewish museum in Prague');
			parent::__construct();
		}

		public function checkStatus() {
			return array(
				'description' => $this->getDescription(),
				'errors' => array(),
				'warnings' => array(),
				'available' => true //((bool)$this->opo_config->get('enabled'))
			);
		}



		# -------------------------------------------------------
		# -------------------------------------------------------
		/**
		 * Insert activity menu
		 */
		public function hookRenderMenuBar($pa_menu_bar) {

			//var_export($pa_menu_bar["manage"]['navigation']['system_config']['navigation']['locales']);

        //if (isset($pa_menu_bar['manage']['system_config'])) {
					//$pa_menu_bar['manage']['navigation']['system_config']["navigation"]["idnoverify"]
					/*$pa_menu_bar['manage']['navigation']['system_config']["navigation"]["jmp"]
 					= array(
            'displayName' => 'Test tiskové sestavy 1',
            "default" => array(
              'module' => 'jmp',
              'controller' => 'Print',
              'action' => 'SbirkovaSestava'
            )
          );*/
/*
					$pa_menu_bar['manage']['navigation']["jmp"]
 					= array(
            'displayName' => 'JMP: outTest tiskové sestavy',
            "default" => array(
              'module' => 'jmp',
							'controller' => 'Print',
              'action' => 'SbirkovaSestava'
            )
          );*/
          
		  
		  $pa_menu_bar["find"]['navigation']['entities']['submenu']['navigation']['victim']=array(
			  'displayName'=>'Oběti ŽMP (DEMO)',
			  "default" =>array('module'=>'jmp','controller'=>'Victim','action'=>'Search'),
			  'is_enabled' => 1);
			

		  $pa_menu_bar["jmp"]
		  = array(
			  'displayName' => 'JMP',
			 
			  	'navigation'=>array(
							"about"=>array(
								'displayName' => 'O této verzi',
								//"default" => "/jmp/Print/About",
								"default" => array(
									'module' => 'jmp',
									 'controller' => 'Print',
									'action' => 'About'
								  )							
							),														
								
						'idno'=>array(
							'is_enabled' => 1,
							"displayName"=>"Idno",							 
							'requires' => array('action:jmp_stats'=>"OR"),
							"submenu"=>array(
								"navigation"=>array(					

									'jmp_stats_idno_regexp'=>array('displayName' => 'Test regulérních výrazů',		"default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'IdnoRegexp'),'is_enabled' => 1),
									'jmp_stats_idno_reindex'=>array('displayName' => 'Reindexace',"default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'IdnoSearchRebuild'),'is_enabled' => 1),
									'jmp_stats_idno_sequences'=>array('displayName' => 'Test na duplicity',"default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'IdnoSequences'),'is_enabled' => 1),
								
								)
								)
							
							
						),


						'places'=>array(
							'is_enabled' => 1,
							"displayName"=>"Místa",
							"default" => "/jmp/Stats/PlacesMap"	, 
							"submenu"=>array(
								"navigation"=>array(					

									'jmp_stats_places_map'=>array('displayName' => 'Mapa míst',"default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'PlacesMap'),'is_enabled' => 1),
									'jmp_stats_places_csv'=>array('displayName' => 'Export  míst do CSV','requires' => array('action:jmp_stats'=>"OR"),"default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'PlacesCSV') ,'is_enabled' => 1),
								))
							
							
						),

						'images'=>array(
							'is_enabled' => 1,
							"displayName"=>"Obrázky",
							'requires' => array('action:jmp_stats'=>"OR"),
							"submenu"=>array(
								"navigation"=>array(					

									
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ImagesImport'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Kolik bylo kdy naimportováno reprezentací ",'is_enabled' => 1),    
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ImageThumbTester'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Kontrola IIF obrázků",'is_enabled' => 1),

									)
									)
							
							
						),



						
						'keywords'=>array(
							'is_enabled' => 1,
							"displayName"=>"Klíčová slova",
							//"default" => "/jmp/Stats/PlacesMap"	, 
							"submenu"=>array(
								"navigation"=>array(														
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ListItems'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Seznamy a položky",'is_enabled' => 1),      
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ListItemsGraph'),"displayName"=>"Graf klíčových slov",'is_enabled' => 1),   
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ListItemsSex'),"displayName"=>"Seznamy a položky - pohlaví",'is_enabled' => 1),      
									)
							)
							
							
						),

						'users'=>array(
							'is_enabled' => 1,
							"displayName"=>"Uživatelé",
							//"default" => "/jmp/Stats/PlacesMap"	, 
							"submenu"=>array(
								"navigation"=>array(					

									//todo ?from=100&to=1000
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'UserCopy'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Kopírování práv uživatele",'is_enabled' => 1),      
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'UserEditors'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Který uživatel používá které editory ",'is_enabled' => 1),    
									array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'UserPrivs'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Uživatélé, role, skupiny",'is_enabled' => 1),      



									)
							)
							
							
						),



						"stats"=>array(
							'is_enabled' => 1,
								'displayName' => 'Ostatní',
								
								"submenu"=>array(
									"navigation"=>array(

//todo?set=1&about=about"
										"drps"=>array(
											"default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'DRPSObjects'),
											'requires' => array('action:jmp_stats'=>"OR"),
											"displayName"=>"Kopie sady objektů do DRPS ",
											'is_enabled' => 1,
											'parameters' => array(
												'set'=>1,
												'about'=>'about'
											)
										),

 "drps2"=>array(
                                                                                        "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'DRPSexport'),
                                                                                        "displayName"=>"Kopie sady 647 do DRPS ",
                                                                                        'is_enabled' => 1
                                                                                      
                                                                                ),


										
										"11"=>array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ElementsRequired'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Které elementy jsou vyžadovány??? asi. ",'is_enabled' => 1),    
										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'EntitiesTSV'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Výpis tsv aktérů ŽMP (omezené) ",'is_enabled' => 1),    
										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'EntitiesChanges'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"Výpis aktérů ITI s datem změny",'is_enabled' => 1),    

										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'Fonts'),"displayName"=>"fonty",'is_enabled' => 1),

										


										

									
										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'InterstitialAttribute'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"CSV, kolikrát je interstitial attribute použit",'is_enabled' => 1),    
										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'InterstitialIdno'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"CSV, IDNO užívající interstitial ",'is_enabled' => 1),    

										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'ObjectsPrimaryImageHidden'),"displayName"=>"Objekty, co mají primární reprezentaci skrytou",'is_enabled' => 1),

										
										array( "default" =>array('module'=>'jmp','controller'=>'Stats','action'=>'RelationshipTypesCsv'),'requires' => array('action:jmp_stats'=>"OR"),"displayName"=>"CSV s vztahy a kolikrát jsou použity",'is_enabled' => 1),

										
									)
							)
									)




					)
					
					

		  )

		   ;


			
					
					
					
/*					
			$pa_menu_bar["test"]
				= array(
        'displayName' => 'Jste v testu',
        "default" => array(
          'module' => 'jmp',
					'controller' => 'Print',
          'action' => 'About'
        )
      );
*/


/* 
Nefunguje

					$pa_menu_bar["idnoverify"]
 					= array(
            'displayName' => 'Test tiskové sestavy 3',
            "default" => array(
              'module' => 'jmp',
							'controller' => 'Print',
              'action' => 'SbirkovaSestava'
            )

          );
*/

			return $pa_menu_bar;
		}
		# -------------------------------------------------------
		
		# -------------------------------------------------------
		/**
		 * Add plugin user actions
		 */
		static function getRoleActionList() {
			return array(
				'jmp_tiskovasestava' => array(
						'label' => _t('Can use Print report for Jewish Museum'),
						'description' => _t('User can print data from special print report.')
					),
				'jmp_stats' => array(
						'label' => 'Can show statistics for Jewish Museum',
						'description' => ('User can show custom statistics.')
				)	
			);
		}
		# -------------------------------------------------------
				
}
