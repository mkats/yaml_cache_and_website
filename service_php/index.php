<?php

require_once 'SocketListener.php';

// Network-related constants
define('SERVICE_LSTN_ADDR', "localhost" ); // getHostByName(getHostName())
define('SERVICE_LSTN_PORT', getenv('SERVICE_LSTN_PORT')); // "8989"

// Meggage-related constants
define('REQ_REPEAT', "/REQ_HELLO/\n");
define('REQ_STORE_YAML', "/REQ_STORE_YAML/\n");
define('REQ_GET_YAML', "/REQ_GET_YAML/\n");
define('REQ_GET_ALL_YAMLS', "/REQ_GET_ALL_YAMLS/\n");
define('RES_OK', "/RES_OK/\n");
define('RES_ERR', "/RES_ERR/\n");
define('RES_WRN', "/RES_WRN/\n");
define('END_OF_MSG', "/END/\n");


set_time_limit(100);

$socketListener= new SocketListener();
$socketListener->start();