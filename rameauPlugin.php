<?php
/* ----------------------------------------------------------------------
 * simpleListEditor
 * ----------------------------------------------------------------------
 * List & list values editor plugin for Providence - CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Plugin by idéesculture (www.ideesculture.com)
 * This plugin is published under GPL v.3. Please do not remove this header
 * and add your credits thereafter.
 *
 * File modified by :
 * ----------------------------------------------------------------------
 */
 
	class rameauPlugin extends BaseApplicationPlugin {
		# -------------------------------------------------------
		protected $description = 'Rameau for CollectiveAccess';
		# -------------------------------------------------------
		private $opo_config;
		private $ops_plugin_path;
		# -------------------------------------------------------
		public function __construct($ps_plugin_path) {
			$this->ops_plugin_path = $ps_plugin_path;
			$this->description = _t('Rameau plugin');
			parent::__construct();
			$this->opo_config = Configuration::load($ps_plugin_path.'/conf/rameau.conf');
		}
		# -------------------------------------------------------
		/**
		 * Override checkStatus() to return true - the statisticsViewerPlugin always initializes ok... (part to complete)
		 */
		public function checkStatus() {
			return array(
				'description' => $this->getDescription(),
				'errors' => array(),
				'warnings' => array(),
				'available' => ((bool)$this->opo_config->get('enabled'))
			);
		}
		# -------------------------------------------------------
		/**
		 * Add plugin user actions
		 */
		static function getRoleActionList() {
			return array();
		}
        # -------------------------------------------------------
        /**
         * Insert activity menu
         */
        public function hookRenderMenuBar($pa_menu_bar) {
            if ($o_req = $this->getRequest()) {
                //if (!$o_req->user->canDoAction('can_use_media_import_plugin')) { return true; }
                $pa_menu_bar["manage"]["navigation"]['rameau_menu'] = array(
                    'displayName' => _t('Rameau'),
                    "default" => array(
                        'module' => 'rameau',
                        'controller' => 'rameau',
                        'action' => 'Index'
                    )
                );
                //var_dump($pa_menu_bar["find"]["navigation"]);die();

            }

            return $pa_menu_bar;
        }
	}
?>
