<?php

namespace model\administrator\menu;

class Menu {

    private $name;
    private $directory;
    private $icon;
    private $color;

    public function __construct($name, $directory, $icon, $color) {
        $this->name = $name;
        $this->directory = $directory;
        $this->icon = $icon;
        $this->color = $color;
    }

    public function getName() {
        return $this->name;
    }

    public function getDirectory() {
        return $this->directory;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getColor() {
        if (empty($this->color)) {
            $this->color = $this->RandomColor();
        }
        return $this->color;
    }

    private function RandomColor() {
        $colors = array('blue', 'green', 'red', 'yellow', 'pink', 'purple', 'lime', 'magenta', 'teal', 'turquoise', 'green-sea', 'emerald', 'nephritis', 'peter-river', 'belize-hole', 'amethyst', 'wisteria', 'wet-asphalt', 'midnight-blue', 'sun-flower', 'orange', 'carrot', 'pumpkin', 'alizarin', 'pomegranate', 'clouds', 'silver', 'concrete', 'asbestos');
        return $colors[rand(1, 29)];
    }

}

?>
