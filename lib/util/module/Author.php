<?php

namespace lib\util\module;

class Author {

    private $name;
    private $email;

    public static function createInstance() {
        return new Author();
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

}
