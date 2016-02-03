<?php

namespace model\administrator\menu;

use lib\util\module\Module;

class Menu
{
    private $name;
    private $directory;
    private $icon;
    private $color;

    public function __construct($directory, Module $moduleConfig)
    {
        $this->name = $moduleConfig->getName();
        $this->directory = $directory;
        $this->icon = $moduleConfig->getTile()->getIcon();
        $this->color = $moduleConfig->getTile()->getColor();
        if (empty($this->color)) {
            $r = rand(1, 29);
            $this->color = $moduleConfig->getTile()->getAllowedColors()[$r];
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getColor()
    {
        return $this->color;
    }
}