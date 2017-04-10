<?php

/**
 * The ServiceClient is the interface of this website for communicating with
 * the service, via TCP.
 *
 * @author michalis
 */
class ServiceClient {

	/**
	 * Sends a request to the service and returns the service's response.
	 * 
	 * Requests are actually plain text messages. The type of request is
	 * defined by a message prefix. The end of the message is specified by
	 * a message postfix.
	 * 
	 * The response is also a plain text message, which can be JSON-formatted,
	 * or a simple error message.
	 * 
	 * This file implements the Request and Response classes, which add
	 * provide some added functionality.
	 * 
	 * @param string $reqType One of the following values: REQ_REPEAT,
	 *    REQ_STORE_YAML, REQ_GET_YAML, REQ_GET_ALL_YAMLS
	 * @param string $reqMsg The body of the message.
	 * @return string|\ServiceResponse In case the service responds with an
	 *    error message, it is returned 'as it is'.
	 */
	public function sendReq($reqType, $reqMsg) {
		$logs= "";
		$logs .= "<span class='dropzone-msg-success'>ServiceClient#sendReq(...) is executed.</span></br>";
		
		// Open a TCP socket to communicate with the service		
		set_error_handler(function() { /* ignore errors */
		});
		$fp = fsockopen(SERVICE_LSTN_ADDR, SERVICE_LSTN_PORT, $errno, $errstr, 30);
		restore_error_handler();
		if (!$fp) {
			//echo "$errstr ($errno)<br />\n";
			$logs .= "<span class='dropzone-msg-error'>Connection to service failed.</span></br>";
			return $logs;
		} else {
			$logs .= "<span class='dropzone-msg-success'>Connected to service.</span></br>";

			// Write the file to the TCP socket
			$serviceReq= new ServiceRequest($reqType, $reqMsg);
			$serviceReqStr= $serviceReq->prepare();
			if (!fwrite($fp, $serviceReqStr)) {
				$logs .= "<span class='dropzone-msg-error'>Message not sent.</span></br>";
				return $logs;
			} else {
				$logs .= "<span class='dropzone-msg-success'>Message sent.</span></br>";
			}
			fflush($fp);

			// Read the service response from the TCP socket
			$serviceResStr = "";
			while (strpos($serviceResStr, END_OF_MSG) === FALSE) {
				$buffer = fgets($fp, 128);
				$serviceResStr .= $buffer;
				//file_put_contents($targetPath . 'indata.txt', $serviceResStr);
			}
			$serviceRes= new ServiceResponse($serviceResStr);
			$serviceRes->logs = $logs . $serviceRes->logs;
			fclose($fp);
			return $serviceRes;
		}
	}

}

/**
 * Class for forming requests to the service.
 */
class ServiceRequest {

	public $type;
	public $message;
	private $preparedReq;

	/**
	 * Contstuctor
	 * 
	 * @param string $type One of the following values: REQ_REPEAT,
	 *    REQ_STORE_YAML, REQ_GET_YAML, REQ_GET_ALL_YAMLS
	 * @param string $message The body of the message.
	 */
	function __construct($type, $message) {
		$this->type = $type;
		$this->message = $message;
	}

	/**
	 * Adds the END_OF_MSG postfix to the message, making it ready
	 * to be written to the socket.
	 * @return string
	 */
	function prepare() {
		$this->preparedReq = $this->type . $this->message . END_OF_MSG;
		return $this->preparedReq;
	}

}

/**
 * Class for parsing responses from the service.
 */
class ServiceResponse {

	/**
	 * @var string Indicates if the service responded with data or an error
	 * message. Has one of the following values: RES_OK, RES_ERR, or RES_WRN.
	 */
	public $type;
	
	/**
	 * @var string Contains the servier message without the prefix and postfix.
	 */
	public $srvMessage;
	
	/**
	 * @var string HTML-formatted text report about the proper execution of the
	 * requested action, or the errors/warning that occuried. This is mostly
	 * used for demonstration or debugging purposes.
	 */
	public $logs;

	/**
	 * Constructor
	 * 
	 * @param type $serviceResStr
	 */
	function __construct($serviceResStr) {
		// Remove message postfix.
		$serviceResStr = str_replace(END_OF_MSG, "", $serviceResStr);
		// Resolve response type and remove prefix.
		switch ($serviceResStr) {
			case strpos($serviceResStr, RES_OK) !== FALSE:
				$this->type= RES_OK;
				$this->srvMessage = str_replace(RES_OK, "", $serviceResStr);
				$this->logs = "<span class='dropzone-msg-success'>Service said: ".$this->srvMessage."</span></br>";
				break;
			case strpos($serviceResStr, RES_WRN) !== FALSE:
				$this->type= RES_WRN;
				$this->srvMessage = str_replace(RES_WRN, "", $serviceResStr);
				$this->logs = "<span class='dropzone-msg-warning'>Service said: ".$this->srvMessage."</span></br>";
				break;
			case strpos($serviceResStr, RES_ERR) !== FALSE:
				$this->type= RES_ERR;
				$this->srvMessage = str_replace(RES_ERR, "", $serviceResStr);
				$this->logs = "<span class='dropzone-msg-error'>Service said: ".$this->srvMessage."</span></br>";
				break;
			default:
				$this->type= RES_WRN;
				$this->srvMessage = $serviceResStr;
				$this->logs = "<span class='dropzone-msg-warning'>Service did not specify response type.</span></br>";
				$this->logs .= "<span class='dropzone-msg-warning'>Service said: ".$this->srvMessage."</span></br>";
		}
	}

}
