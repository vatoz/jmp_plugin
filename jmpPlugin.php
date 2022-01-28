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
					
			$pa_menu_bar['manage']['navigation']["jmp_about"]
 					= array(
            'displayName' => 'JMP: O této verzi',
            "default" => array(
              'module' => 'jmp',
							'controller' => 'Print',
              'action' => 'About'
            )
          );
		
					$pa_menu_bar['manage']['navigation']["jmp_stats"]
							= array(
								'displayName' => 'JMP: Statistiky',
								"default" => array(
									'module' => 'jmp',
									'controller' => 'Stats',
									'action' => 'About'
								)
							);	
			
					
					
					
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
