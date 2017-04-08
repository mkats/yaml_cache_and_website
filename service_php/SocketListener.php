<?php

require_once 'Router.php';

/**
 * Creates and binds a TCP socket, and listens for incoming connections. When
 * a connection is established, the SocketListener reads data from the socket
 * until a complete request message is received. Then, the message is sent to
 * the a Router object. When the router returns a response message, this
 * message is written to the socket. After the message is written, the
 * SocketListener expects the client to close the connection.
 * 
 * A request/response message begins with a special prefix and ends with a
 * special postfix. These prefixes and postfixes are defined as constants
 * in the file "index.php".
 *
 * @author Michalis Katsarakis
 */
class SocketListener {

	public function start() {
		// Create and bind TCP socket.
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		$isbind = socket_bind($socket, SERVICE_LSTN_ADDR, SERVICE_LSTN_PORT);
		if (!($isbind)) {
			echo "Trouble Binding\n";
			return 0;
		} else {
			echo "Socket bound.\n";
		}
		// Listen for incoming connections on the TCP socket.
		while ($socket) {
			socket_listen($socket, 10);
			$newsock = socket_accept($socket);
			if ($newsock) {
				// Read request from TCP socket
				// It should be either a YAML document or a request code.
				$request = "";
				while (strpos($request, END_OF_MSG) === FALSE) {
					$buffer = @socket_read($newsock, 512); // ,PHP_NORMAL_READ
					$request .= $buffer;
				}
				$request = str_replace(END_OF_MSG, "", $request);
				echo "-----------------\n";
				echo "Received request:\n";
				echo "-----------------\n";
				echo $request . "\n";
				
				// Process request.
				$router= new Router();
				$response= $router->route($request);				
				
				// Send back a response.
				$response= $response.END_OF_MSG;
				socket_write($newsock, $response);
				// The other side should now close the connection.

				echo "--------------\n";
				echo "Sent response:\n";
				echo "--------------\n";
				echo $response."\n";
			} else {
				echo "Screwed up :-(\n";
			}
		}
		socket_close($socket);
		echo "done\n";
	}
}
