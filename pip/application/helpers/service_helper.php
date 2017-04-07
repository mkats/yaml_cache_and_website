<?php

/**
 * The ServiceClient is responsible for communicating with the service via TCP.
 *
 * @author michalis
 */
class ServiceClient {

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

class ServiceRequest {

	public $type;
	public $message;
	private $preparedReq;

	function __construct($type, $message) {
		$this->type = $type;
		$this->message = $message;
	}

	function prepare() {
		$this->preparedReq = $this->type . $this->message . END_OF_MSG;
		return $this->preparedReq;
	}

}

class ServiceResponse {

	public $type;
	public $srvMessage;
	public $logs;

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
