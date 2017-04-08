<?php

require_once 'Controller.php';

/**
 * The Router removes the request prefix from an incoming request message and
 * calls the appropariate controller method, passing as in input argument the
 * request message.
 * 
 * @author Michalis Katsarakis
 */
class Router {

	/**
	 * Removes the request prefix from an incoming request message and
	 * calls the appropariate controller method, passing as in input argument
	 * the request message.
	 * @param string $request
	 * @return string
	 */
	public function route($request) {
		echo "In router\n";
		$response = "";
		$controller = new Controller();
		switch ($request) {
			case strpos($request, REQ_REPEAT) !== FALSE:
				$request = str_replace(REQ_REPEAT, "", $request);
				$response = $controller->repeat($request);
				break;
			case strpos($request, REQ_STORE_YAML) !== FALSE:
				$request = str_replace(REQ_STORE_YAML, "", $request);
				$response = $controller->storeYaml($request);
				break;
			case strpos($request, REQ_GET_YAML) !== FALSE:
				$request = str_replace(REQ_GET_YAML, "", $request);
				$response = $controller->getYaml($request);
				break;
			case strpos($request, REQ_GET_ALL_YAMLS) !== FALSE:
				$request = str_replace(REQ_GET_ALL_YAMLS, "", $request);
				$response = $controller->getAllYamls($request);
				break;
			default:
				echo "Default case.\n";
				$response = RES_ERR . "Bad request.";
		}
		return $response;
	}

}
