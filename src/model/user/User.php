<?php

namespace model\user;

use lib\util\Object;

/**
 * @Entity
 * @Table(name="user")
 */
class User extends Object {

    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** @Column(type="string", nullable=true) */
    private $middleName;

    /** @Column(type="string") */
    private $lastName;

    /** @Column(type="string", length=32) */
    private $login;

    /** @Column(type="string", length=32) */
    private $password;

    /** @Column(type="datetime", nullable=true) */
    private $lastLogin;

    /**
     * @ManyToOne(targetEntity="model\user\accountType\AccountType", cascade={"merge"}) 
     * @JoinColumn(name="accountType_id", referencedColumnName="id")
     */
    private $accountType;

    /** @Column(type="string", length=1) */
    private $status;
    
    function __construct() {
        $this->status = '1';                
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function getAccountType() {
        return $this->accountType;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setId($id) {
        $this->id = $id;        
    }

    public function setName($name) {
        $this->name = $name;        
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;        
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;        
    }

    public function setLogin($login) {
        $this->login = $login;        
    }

    public function setPassword($password) {
        $this->password = md5($password);        
    }

    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;        
    }

    public function setAccountType($accountType) {
        $this->accountType = $accountType;        
    }

    public function setStatus($status) {
        $this->status = $status;        
    }

    public function getCompleteName() {
        return trim(sprintf('%s %s %s', $this->nome, $this->nomeDoMeio, $this->sobrenome));
    }

    public function getStatusStr() {
        return ($this->Status) ? 'ATIVADO' : 'DESATIVADO';
    }

}
