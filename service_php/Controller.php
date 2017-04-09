<?php

require_once 'helper.php';

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
		$parseResult= parse_summarize_yaml($yamlDoc);
		switch ($parseResult["isValid"]) {
			case YAML_INVALID:
				$response = RES_ERR."Invalid YAML syntax.";
				break;
			case YAML_EMPTY:
				$response = RES_WRN."No nodes found in YAML document.";
				break;
			case YAML_VALID:
				// Store YAML file in cache
				$handle= current_time_millis().".yml";
				$mem= new Memcached();
				$mem->addServer('127.0.0.1',11211);
				$succeed= $mem->add($handle,$yamlDoc);
				if ($succeed) {
					if ($mem->get("yaml_list") === FALSE)
						$mem->add("yaml_list", "");
					$yaml_list= $mem->get("yaml_list");
					$yaml_list.= $handle.";";
					$mem->set("yaml_list", $yaml_list);
					// Debugging
					echo "Handle: $handle\n";
					echo "Stored YAML doc:\n".$mem->get($handle)."\n";
					echo "Stored yaml_list:\n".$mem->get("yaml_list")."\n";
				}
				// Prepare response
				$resArray= array();
				$resArray["handle"]= $handle;
				if ($succeed) {
					$resArray["top_level_nodes"]= $parseResult["top_level_nodes"];
					$response = RES_OK.json_encode($resArray);
				} else {
					$response = RES_ERR."Handle \"$handle\" already exists.";
				}
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
