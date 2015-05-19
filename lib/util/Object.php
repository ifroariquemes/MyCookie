<?php

namespace lib\util;

use Doctrine\ORM\EntityManager;

class Object {

    public function save() {
        /* @var $_EntityManager EntityManager */
        global $_EntityManager;
        $_EntityManager->beginTransaction();
        $_EntityManager->persist($this);
        $_EntityManager->commit();
        $_EntityManager->flush();
    }

    public function delete() {
        /* @var $_EntityManager EntityManager */
        global $_EntityManager;
        $_EntityManager->beginTransaction();
        $_EntityManager->remove($this);
        $_EntityManager->commit();
        $_EntityManager->flush();
    }

    /**
     * 
     * @global \Doctrine\ORM\EntityManager $_EntityManager
     * @return \Doctrine\ORM\QueryBuilder
     */
    public static function select($alias, $fields = null) {
        global $_EntityManager;
        $_EntityManager = Database::EntityManager();
        return $_EntityManager->createQueryBuilder()->select(is_null($fields) ? $alias : $fields)->from(get_called_class(), $alias);
    }

    /**
     * @global \Doctrine\ORM\EntityManager $_EntityManager
     * @return \Doctrine\ORM\Query\Expr
     */
    public static function expr() {
        global $_EntityManager;
        $_EntityManager = Database::EntityManager();
        return $_EntityManager->createQueryBuilder()->expr();
    }

}
