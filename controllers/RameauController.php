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

    require_once(__CA_MODELS_DIR__.'/ca_lists.php');
    require_once(__CA_MODELS_DIR__.'/ca_list_items.php');
    require_once(__CA_MODELS_DIR__.'/ca_objects.php');
    require_once(__CA_MODELS_DIR__.'/ca_object_labels.php');
    require_once(__CA_LIB_DIR__."/Plugins/PDFRenderer/PhantomJS.php");
	error_reporting(E_ERROR);

 	class RameauController extends ActionController {
 		# -------------------------------------------------------
  		protected $opo_config;		// plugin configuration file
        protected $opa_list_of_lists; // list of lists
        protected $opa_listIdsFromIdno; // list of lists
        protected $opa_locale; // locale id
		private $opo_list;
 		# -------------------------------------------------------
 		# Constructor
 		# -------------------------------------------------------

 		public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
            parent::__construct($po_request, $po_response, $pa_view_paths);

 			// NO RIGHTS CHECKED FOR NOW
 			/*if (!$this->request->user->canDoAction('can_use_simplelisteditor_plugin')) {
 				$this->response->setRedirect($this->request->config->get('error_display_url').'/n/3000?r='.urlencode($this->request->getFullUrlPath()));
 				return;
 			}*/

 			$this->opo_config = Configuration::load(__CA_APP_DIR__.'/plugins/rameau/conf/rameau.conf');
			$this->opo_list = new ca_lists("object_types");
        }

 		# -------------------------------------------------------
 		# Functions to render views
 		# -------------------------------------------------------
 		public function Index($type="") {
 			$id= $this->request->getParameter("id", pInteger);
			$type_id=$this->opo_config->get("root_type");
			$query = "SELECT ca_object_labels.object_id, idno, name FROM ca_objects left join ca_object_labels ON ca_objects.object_id = ca_object_labels.object_id AND ca_object_labels.is_preferred=1";
			if(!$id) {
				$this->view->setVar("object_id", false);
				$query .= " WHERE ca_objects.type_id =".$type_id." and deleted=0";
			} else {
				$item = new ca_objects($id);
				$this->view->setVar("item", $item);
				$this->view->setVar("object_id", $id);
				$query .= " WHERE parent_id =".$id." and deleted=0";
			}
			$o_data = new Db();
			$this->view->setVar("qr_result", $o_data->query($query));
 			$this->view->setVar("template", $this->opo_config->get("template"));
            $this->render('index_html.php');
 		}

 		public function Fetch($type="") {
			
			print $this->render('fetch_html.php');
			exit();
		}

 		public function Create($type="") {
			print $this->render('create_html.php');
			exit();
		}

 		public function Create2($type="") {
			print $this->render('create2_html.php');
			exit();
		}
		
 		public function Export() {
	 		//error_reporting(E_ALL);
			$id= $this->request->getParameter("id", pInteger);
			$this->view->setVar("object_id", $id);
			$item = new ca_objects($id);
			$this->view->setVar("item", $item);
			$this->view->setVar("template", $this->ConvertValuesToIdsInsideTemplate($this->opo_config->get("printTemplate")));
			$result = $this->render('export_html.php');
			print $result;
			exit();
		}

        public function Pdf() {
            //error_reporting(E_ALL);
            $id= $this->request->getParameter("id", pInteger);
            $this->view->setVar("object_id", $id);

            $item = new ca_objects($id);
            $this->view->setVar("item", $item);
            $this->view->setVar("template", $this->ConvertValuesToIdsInsideTemplate($this->opo_config->get("printTemplate")));
            $result = $this->render('pdf_html.php');

            $export_file = "archives_export_pdf_".$id.".html";
            $path = __DIR__."/../temp/".$export_file;
            unlink($path);
            file_put_contents($path, $result);
            exec("cd ".__DIR__."/../temp && phantomjs rasterize.js archives_export_pdf_".$id.".html archives_export_pdf_".$id.".pdf A4", $output);

            $files = [];
            //var_dump(__DIR__."/../temp/".$id);die();
            foreach(scandir(__DIR__."/../temp/".$id) as $file) {
                if(strpos($file,"_")>0 && (substr($file, -4)==".pdf")) {
                    $num = reset(explode("_", $file));
                    if(!$num*1) {
                        continue;
                    }
                    $files[] = $id."/".$file;
                }

            }
            $file = __DIR__."/../temp/".$id.".pdf";
            unlink($file);
            
            $command = "sleep 3 && cd ".__DIR__."/../temp && pdftk ".implode(" ", $files)." archives_export_pdf_".$id.".pdf cat output ".$id.".pdf";
            exec($command, $output);

			print json_encode(
				[
					"message"=>"Le fichier PDF est en cours de création",
					"URL"=>"/gestion/app/plugins/archives/temp/".$id.".pdf"
				]	
			);

			die();
        }

        private function ConvertValuesToIdsInsideTemplate($template) {
            foreach($template as $key=>$template_per_id) {
            	if($key == "_default") continue;
                //print $key."\n";
                $type_id  = $this->opo_list->getItemIDFromListByItemValue("object_types", $key);
                $template[$type_id] = $template[$key];
                unset($template[$key]);
            }
            return $template;

        }
 	}
 ?>
