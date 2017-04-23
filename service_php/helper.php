<?php

/**
 * Returns Unix timestamp in milliseconds.
 * @return long
 */
function current_time_millis() {
	return round(microtime(true) * 1000);
}

/**
 * Converts a microtime value in milliseconds.
 * 
 * By default, microtime() returns a string in the form "msec sec", where sec is
 * the number of seconds since the Unix epoch (0:00:00 January 1,1970 GMT), and
 * msec measures microseconds that have elapsed since sec and is also expressed
 * in seconds.
 * 
 * microtime(true)returns a float, which represents the current time in seconds
 * since the Unix epoch accurate to the nearest microsecond. 
 * 
 * @param float|string $microtime A value returned from microtime(). In case
 * $microtime is a string, it is parsed according to the "msec sec" form and
 * converted back to float.
 * 
 * @return long Unix timestamp in milliseconds.
 */
function microtime_to_mills($microtime) {
	if (is_string($microtime)) {
		$array = explode(" ", $microtime);
		$str = $array[1] . "." . $array[0];
		$microtime = floatval($str);
	}
	return round($microtime * 1000);
}

define('YAML_INVALID', 0);
define('YAML_EMPTY', 1);
define('YAML_VALID', 2);

/**
 * Checks if the input string has valid YAML syntax and computes a summary
 * about it or returns with an error flag, namely either YAML_INVALID or
 * YAML_EMPTY.
 * 
 * The summary of the YAML document is defined as a list of key-value pairs,
 * where the keys are the top-level YAML nodes and the values are integers
 * showing the number of subnodes for each top-level YAML node.
 * 
 * @param string $yamlText YAML data
 * @return array
 */
function parse_summarize_yaml($yamlText) {
	$retval= array("isValid"=>NULL, "top_level_nodes"=>NULL);
	error_clear_last();
	$parsed_yaml = yaml_parse($yamlText);
	$error = error_get_last();
	print_r($error);
	if (strpos($error["message"], 'yaml_parse(): Unexpected event') !== FALSE) { 
		// Case 1: Invalid YAML syntax
		$retval["isValid"]= YAML_INVALID;
	} elseif (empty($parsed_yaml)) {
		// Case 2: Empty YAML document
		$retval["isValid"]= YAML_EMPTY;
	} else {
		// Case 3: YAML document contains keys
		$retval["isValid"]= YAML_VALID;
		$retval["top_level_nodes"]= array();
		foreach ($parsed_yaml as $key => $value) {
			$retval["top_level_nodes"][$key] = count($value);
		}
	}
	return $retval;
}
