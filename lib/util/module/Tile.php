<?php

namespace lib\util\module;

class Tile {

    private $allowedIcons = array();
    private $allowedColors = array('blue', 'green', 'red', 'yellow', 'pink', 'purple', 'lime', 'magenta', 'teal', 'turquoise', 'green-sea', 'emerald', 'nephritis', 'peter-river', 'belize-hole', 'amethyst', 'wisteria', 'wet-asphalt', 'midnight-blue', 'sun-flower', 'orange', 'carrot', 'pumpkin', 'alizarin', 'pomegranate', 'clouds', 'silver', 'concrete', 'asbestos');
    private $icon;
    private $color;
    private $module;
    
    public static function createInstance() {
        return new Tile();
    }
    
    public function getAllowedColors() {
        return $this->allowedColors;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getColor() {
        return $this->color;
    }

    public function setIcon($icon) {
        if (!in_array($icon, $this->allowedIcons)) {
            $this->icon = $icon;
        } else {
            throw new \Exception("The tile icon setted to module $this->module does not exists ($icon).");
        }
        return $this;
    }

    public function setColor($color) {
        if (in_array($color, $this->allowedColors)) {
            $this->color = $color;
        } else {
            throw new \Exception("The tile color setted to module $this->module does not exists ($color).");
        }
        return $this;
    }
    
    public function setModule($module) {
        $this->module = $module;
    }

}
