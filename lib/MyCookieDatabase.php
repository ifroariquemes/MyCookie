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

$_MyCookie = MyCookie::singleton();

$myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();

$_EntityManager = EntityManager::create(array(
            'driver' => "pdo_{$myCookieConfiguration->database->driver}",
            'user' => $myCookieConfiguration->database->user,
            'password' => $myCookieConfiguration->database->password,
            'dbname' => $myCookieConfiguration->database->dbname,
                ), Setup::createAnnotationMetadataConfiguration(array(dirname(__DIR__) . "/src/model/"), $isDevMode));
