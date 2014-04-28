<?php

namespace lib\util;

use Doctrine\ORM\EntityManager;

class Object {
    
    public function Save() {
        /* @var $_EntityManager EntityManager */
        global $_EntityManager;
        $_EntityManager->beginTransaction();
        $_EntityManager->persist($this);
        $_EntityManager->commit();
        $_EntityManager->flush();
    }
    
    /**
     * 
     * @global \Doctrine\ORM\EntityManager $_EntityManager
     * @return \Doctrine\ORM\QueryBuilder
     */
    public static function Select($alias) {        
        global $_EntityManager;
        $_EntityManager = Database::EntityManager();
        return $_EntityManager->createQueryBuilder()->select($alias)->from(get_called_class(), $alias);
    }
    
}

