<?php

namespace lib\util\module;

class Home {

    private $control;
    private $action;

    public static function createInstance() {
        return new Home();
    }

    public function getControl() {
        return $this->control;
    }

    public function getAction() {
        return $this->action;
    }

    public function setControl($control) {
        $this->control = $control;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

}
