<?php

namespace model\user\accountType;

use \lib\util\Object;

/** @Entity @Table("account_type") */
class AccountType extends Object {

    /** @Id @GeneratedValue @Column(type="integer") */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** @Column(type="string") */
    private $flag;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getFlag() {
        return $this->flag;
    }

    public function setId($id) {
        $this->id = $id;        
    }

    public function setName($name) {
        $this->name = $name;        
    }

    public function setFlag($flag) {
        $this->flag = $flag;        
    }

}
