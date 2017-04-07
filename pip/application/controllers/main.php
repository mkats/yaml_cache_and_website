<?php

require_once './application/helpers/service_helper.php';

// Network-related constants
define('SERVICE_LSTN_ADDR', "192.168.56.101");
define('SERVICE_LSTN_PORT', "8989");
// TODO: When executed in a Docker container, get the values of these
// constants from environment variables.

// Meggage-related constants
define('REQ_REPEAT', "/REQ_HELLO/\n");
define('REQ_STORE_YAML', "/REQ_STORE_YAML/\n");
define('REQ_GET_YAML', "/REQ_GET_YAML/\n");
define('REQ_GET_ALL_YAMLS', "/REQ_GET_ALL_YAMLS/\n");
define('RES_OK', "/RES_OK/\n");
define('RES_ERR', "/RES_ERR/\n");
define('RES_WRN', "/RES_WRN/\n");
define('END_OF_MSG', "/END/\n");

class Main extends Controller {
	
	function index() {
		$template = $this->loadView('main_view');
		$template->render();
	}
	
	
	function upload() {
		if (!empty($_FILES)) {
			// For HTTP POST requests that contain a file, respond with an
			// asynchronous JSON or plain text response. This response will be
			// handled by the client-side Javascript script.
			$logs= "";
			
			// Save the POSTed file in the "uploads" folder
			$ds = DIRECTORY_SEPARATOR;
			$storeFolder = 'uploads';
			$tempFile = $_FILES['file']['tmp_name'];          
			$targetPath = dirname( __FILE__ ) . $ds."..".$ds.".." . $ds. $storeFolder . $ds;
			$targetFile =  $targetPath. $_FILES['file']['name'];
			move_uploaded_file($tempFile,$targetFile);
			$logs .= "<span class='dropzone-msg-success'>File uploaded.</span></br>";
			
			$serviceClient= new ServiceClient();
			$serviceRes= $serviceClient->sendReq(REQ_STORE_YAML, file_get_contents($targetFile));
			if (is_object($serviceRes)) {
				$logs .= $serviceRes->logs;
			} else {
				$logs .= $serviceRes;
			}
			
			// For demonstartion purposes, the value of the $logs variable will
			// be sent to the HTTP client, instead of $serviceRes->srvMessage.
			// $logs contains a 1-line report about each step of the
			// communication with the service.
			if (strpos($logs, 'dropzone-msg-error') === FALSE) {
				http_response_code(200);
				exit($logs);
			} else {
				http_response_code(400);
				exit($logs);
			}
		}
		// For HTTP GET requests, render an HTML page with a form that allows
		// the submission of files.
		$template = $this->loadView('upload_view');
		$template->render();
	}
	
	
	function displayYMLs() {
		$ds          = DIRECTORY_SEPARATOR;
		$storeFolder = 'uploads';
		$targetPath = dirname( __FILE__ ) . $ds."..".$ds.".." . $ds. $storeFolder . $ds;
		$ymlFiles= glob($targetPath."*.txt");
		$outputText="";
		foreach ($ymlFiles as $ymlFile) {
			$outputText .= "$ymlFile size " . filesize($ymlFile) . "\n";
		}
		$template = $this->loadView('display_ymls_view');
		$template->set('outputText', $outputText);
		$template->render();
	}
    
}

?>
