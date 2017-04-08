<?php


/**
 * Returns Unix timestamp in milliseconds.
 * @return long
 */
function current_time_millis() {
	return round(microtime(true)*1000);
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
		$array= explode(" ", $microtime);
		$str= $array[1].".".$array[0];
		$microtime= floatval($str);
	}
	return round($microtime*1000);
}

// TODO: Implement
function get_summary_of_parsed_yaml() {
	
}