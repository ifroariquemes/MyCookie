<?php
        const AUTOLOAD_FILE = 'vendor/autoload.php';

if (!file_exists(AUTOLOAD_FILE)) {
    include ('src/view/build/composer-run.php');
    exit;
}

require_once (AUTOLOAD_FILE);

global $_MyCookie;
global $_Config;
global $_User;
global $_Server;
global $_Cache;
global $_Async;
global $_EntityManager;
global $_BaseURL;

$_MyCookie = lib\MyCookie::getInstance();
$gatewayClass = $_MyCookie->getGatewayClass();
$gatewayControl = new $gatewayClass;
$gatewayControl->Route();
