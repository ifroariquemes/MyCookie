<?php

namespace lib\util\module;

class Controller {

    private $name;
    private $submodule;

    public static function createInstance() {
        return new Controller();
    }

    public function getName() {
        return $this->name;
    }

    public function getSubmodule() {
        return $this->submodule;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setSubmodule($submodule) {
        $this->submodule = $submodule;
        return $this;
    }

}
