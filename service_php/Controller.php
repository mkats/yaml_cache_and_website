<?php

/**
 * Serves the clients' requests and prepares a response message.
 *
 * @author Michalis Katsarakis
 */
class Controller {
	
	/**
	 * Echoes a message back to the client. Useful for development/debugging.
	 * @param string $message
	 * @return string
	 */
	public function repeat($message) {
		return $message;
	}
	
	/**
	 * Receives a YAML document in plain-text format, parses it, and stores a
	 * summary of the YAML document in cache, and responds with a JSON object
	 * that contains a handle and the summary of the YAML document.
	 * 
	 * The summary of the YAML document is defined as a list of key-value pairs,
	 * where the keys are the top-level YAML nodes and the values are integers
	 * showing the number of subnodes for each top-level YAML node.
	 * 
	 * In case the received data is not a valid YAML document, an error message
	 * is returned.
	 * 
	 * @param string $yamlDoc The YAML document to parse.
	 * @return string A JSON object containing a handle and the summary of the
	 * YAML document, or an error message.
	 */
	public function storeYaml($yamlDoc) {
		// TODO: Create a unique memcached key (with timestamp)
		// DONE: import function current_time_millis() from umap_server_yii
		
		//echo "----------------------------\n";
		//echo "storeYaml() says: \$yamlDoc: \n";
		//echo "----------------------------\n";
		//echo $yamlDoc."\n";
		
		// Parse the YAML document.
		//set_error_handler(function() { /* ignore errors */ });
		error_clear_last();
		$parsed_yaml = yaml_parse($yamlDoc);
		//restore_error_handler();
		$error = error_get_last();
		print_r($error);
		if (strpos($error["message"], 'yaml_parse(): scanning error encountered during parsing') !== false) { // Case 1: Invalid YAML syntax
			$response = RES_ERR."Invalid YAML syntax.";
		} elseif (empty($parsed_yaml)) { // Case 2: Empty YAML document
			$response = RES_WRN."No nodes found in YAML document.";
		} else { // Case 3: YAML document contains keys
			//echo "------------\n";
			//echo "Parsed YAML:\n";
			//echo "------------\n";
			//print_r($parsed_yaml);
			//$outdata= print_r($parsed_yaml, true);
			// TODO: implement a function get_summary_of_parsed_yaml()
			$resArray = array("handle" => "TODO", "top_level_nodes" => array());
			foreach ($parsed_yaml as $key => $value) {
				$resArray["top_level_nodes"][$key] = count($value);
			}
			$response = RES_OK.json_encode($resArray);
		}
		return $response;
	}
	
	/**
	 * Receives a handle and rerurns a json containing the data cached under
	 * this handle.
	 * 
	 * @param string $handle
	 * @return string
	 */
	public function getYaml($handle) {
		$response= "";
		return $response;
	}
	
	/**
	 * Retrieves the handles of all YAML documents cached, and then retieves all
	 * cached data.
	 *  
	 * @return string
	 */
	public function getAllYamls() {
		$response= "";
		return $response;
	}

}
