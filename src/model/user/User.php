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

    /** @Column(type="string", nullable=true) */
    private $email;

    /** @Column(type="datetime", nullable=true) */
    private $lastLogin;

    /**
     * @ManyToOne(targetEntity="model\user\accountType\AccountType", cascade={"merge"}) 
     * @JoinColumn(name="accountType_id", referencedColumnName="id")
     */
    private $accountType;

    /** @Column(type="string", length=1) */
    private $status;

    /** @Column(type="string", nullable=true) */
    private $code;

    function __construct() {
        $this->status = '1';
        $this->accountType = new accountType\AccountType;
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

    public function getEmail() {
        return $this->email;
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
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
        return $this;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function setLogin($login) {
        $this->login = $login;
        return $this;
    }

    public function setPassword($password) {
        $this->password = md5($password);
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function setAccountType($accountType) {
        $this->accountType = $accountType;
        return $this;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getCompleteName() {
        return trim(sprintf('%s %s %s', $this->name, $this->middleName, $this->lastName));
    }

    public function getStatusStr() {
        return ($this->status) ? 'ATIVADO' : 'DESATIVADO';
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

}
