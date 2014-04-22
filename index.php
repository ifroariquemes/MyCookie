<?php

        const Autoload = 'vendor/autoload.php';

if (!file_exists(Autoload)) {
    include ('src/view/build/composer-run.php');
    exit;
}

require_once (Autoload);

session_start();

/**
 * @var Doctrine\ORM\EntityManager ORM Manager
 */
global $_EntityManager;
/**
 * @var Modules\User\User The logged user
 */
global $_MyCookieUser;
/**
 * @var Lib\TMyCookie Framework management;
 */
global $_MyCookie;

global $_Cache;

global $_Async;

$_MyCookie = lib\MyCookie::singleton();
$gatewayClass = $_MyCookie->getGatewayClass();
$gatewayControl = new $gatewayClass;
$gatewayControl->Route();
