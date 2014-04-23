<?php

namespace lib;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once ('vendor/autoload.php');

$isDevMode = true;

/**
 * @var EntityManager ORM Manager
 */
global $_EntityManager;

/* @var $_MyCookie MyCookie */
global $_MyCookie;

$myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();

$_EntityManager = EntityManager::create(array(
            'driver' => $myCookieConfiguration->database->driver,
            'user' => $myCookieConfiguration->database->user,
            'password' => $myCookieConfiguration->database->password,
            'dbname' => $myCookieConfiguration->database->dbname,
                ), Setup::createAnnotationMetadataConfiguration(array(dirname(__DIR__) . "/src/model/"), $isDevMode));
