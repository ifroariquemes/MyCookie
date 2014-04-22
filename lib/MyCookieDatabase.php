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

$_EntityManager = EntityManager::create(array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => '',
            'dbname' => 'foo', 
                ), Setup::createAnnotationMetadataConfiguration(array(dirname(__DIR__) . "/src/model/"), $isDevMode));
