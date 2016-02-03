<?php

namespace lib\util;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Database
{
    const DevMode = true;

    private $entityManager;

    /**
     * The instance for singleton
     * @var Database 
     */
    private static $instance;

    /**
     * The singleton method
     * @return Database
     */
    private static function getInstance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $myCookieConfiguration = json_decode(file_get_contents('config.json'));
        $this->entityManager = EntityManager::create(array(
                    'driver' => "pdo_{$myCookieConfiguration->database->driver}",
                    'user' => $myCookieConfiguration->database->user,
                    'password' => $myCookieConfiguration->database->password,
                    'dbname' => $myCookieConfiguration->database->dbname,
                        ), Setup::createAnnotationMetadataConfiguration(array("src/model/"), Database::DevMode));
    }

    public static function EntityManager()
    {
        return Database::getInstance()->entityManager;
    }
}
global $_EntityManager;
$_EntityManager = Database::EntityManager();
