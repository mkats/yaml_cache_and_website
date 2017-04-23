<?php

require_once './application/helpers/service_helper.php';

// Network-related constants
define('SERVICE_LSTN_ADDR', "localhost");
define('SERVICE_LSTN_PORT', getenv('SERVICE_LSTN_PORT'));

// Message-related constants
define('REQ_REPEAT', "/REQ_HELLO/\n");
define('REQ_STORE_YAML', "/REQ_STORE_YAML/\n");
define('REQ_GET_YAML', "/REQ_GET_YAML/\n");
define('REQ_GET_ALL_YAMLS', "/REQ_GET_ALL_YAMLS/\n");
define('RES_OK', "/RES_OK/\n");
define('RES_ERR', "/RES_ERR/\n");
define('RES_WRN', "/RES_WRN/\n");
define('END_OF_MSG', "/END/\n");

class Main extends Controller {

	/**
	 * Just renders the homepage of this website.
	 */
	function index() {
		$template = $this->loadView('main_view');
		$template->render();
	}

	/**
	 * Provides functionality for uploading files.
	 * 
	 * The files are sent to the service via TCP for syntax check and caching.
	 */
	function upload() {
		if (!empty($_FILES)) {
			// For HTTP POST requests that contain a file, respond with an
			// asynchronous JSON or plain text response. This response will be
			// handled by the client-side Javascript script.
			$logs = "";

			// Save the POSTed file in the "uploads" folder
			$ds = DIRECTORY_SEPARATOR;
			$storeFolder = 'uploads';
			$tempFile = $_FILES['file']['tmp_name'];
			$targetPath = dirname(__FILE__) . $ds . ".." . $ds . ".." . $ds . $storeFolder . $ds;
			$targetFile = $targetPath . $_FILES['file']['name'];
			move_uploaded_file($tempFile, $targetFile);
			$logs .= "<span class='dropzone-msg-success'>File uploaded.</span></br>";

			$serviceClient = new ServiceClient();
			$serviceRes = $serviceClient->sendReq(REQ_STORE_YAML, file_get_contents($targetFile));
			if (is_object($serviceRes)) {
				$logs .= $serviceRes->logs;
			} else {
				$logs .= $serviceRes;
			}

			// For demonstartion purposes, the value of the $logs variable will
			// be sent to the HTTP client, instead of $serviceRes->srvMessage.
			// $logs contains a 1-line report about each step of the
			// communication with the service.
			if (strpos($logs, 'dropzone-msg-error') === FALSE && strpos($logs, 'dropzone-msg-warning') === FALSE) {
				http_response_code(200);
				//exit($logs);
				exit($serviceRes->srvMessage);
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

	/**
	 * Displays all YAML documents that are cached by the service.
	 * 
	 * For each YAML document, a summary is displayed, together with its
	 * handle and a download link.
	 */
	function displayAllYamls() {
		$serviceClient = new ServiceClient();
		$serviceRes = $serviceClient->sendReq(REQ_GET_ALL_YAMLS, "");
		$template = $this->loadView('display_all_ymls_view');
		$template->set('serviceRes', $serviceRes);
		$template->render();
	}

	/**
	 * Allows the user to download a specific YAML document from the service's
	 * cache. The document is identified by its unique handle.
	 * 
	 * @param type $handle The handle of the YAML document to download.
	 */
	function displayYaml($handle) {
		$serviceClient = new ServiceClient();
		$serviceRes = $serviceClient->sendReq(REQ_GET_YAML, $handle);
		if ($serviceRes->type == RES_OK) {
			http_response_code(200);
			header("Content-Disposition: attachment; filename=\"$handle\"");
			// Counter-measures for IE6 bugs:
			header("Pragma: public");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			
			exit($serviceRes->srvMessage);
		} else {
			http_response_code(404);
			exit($serviceRes->srvMessage);
		}
	}

}

?>
