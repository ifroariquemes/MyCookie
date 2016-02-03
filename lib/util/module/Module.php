<?php

namespace lib\util\module;

class Module
{
    private $names;
    private $description;
    private $authors;
    private $version;
    private $creationDate;
    private $lastReleaseDate;
    private $tile;
    private $accesses;
    private $home;
    private $controllers;

    public function __construct()
    {
        $this->names = array();
        $this->tile = new Tile();
        $this->home = new Home();
        $this->authors = array();
        $this->controllers = array();
        $this->accesses = array();
    }

    public static function createInstance()
    {
        return new Module();
    }

    public function getName()
    {
        global $_MyCookie;
        global $_Config;        
        if($_Config->lang === 'user') {
            $lng = $_MyCookie->getUserLanguage();            
        } else {
            $lng = $_Config->lang;
        }
        if (array_key_exists($lng, $this->names)) {
            return $this->names[$lng];
        } else {
            return $this->names[0];
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getLastReleaseDate()
    {
        return $this->lastReleaseDate;
    }

    /**
     * 
     * @return Tile
     */
    public function getTile()
    {
        return $this->tile;
    }

    public function getAccesses()
    {
        return $this->accesses;
    }

    /**
     * 
     * @return Home
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * 
     * @return mixed
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    public function setName($name, $lang = null)
    {
        if (is_null($lang)) {
            $this->names[0] = $name;
        } else {
            $this->names[$lang] = $name;
        }
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function setLastReleaseDate($lastReleaseDate)
    {
        $this->lastReleaseDate = $lastReleaseDate;
        return $this;
    }

    public function setTile(Tile $tile)
    {
        $tile->setModule($this->names[0]);
        $this->tile = $tile;
        return $this;
    }

    public function setHome(Home $home)
    {
        $this->home = $home;
        return $this;
    }

    public function addAuthor(Author $author)
    {
        array_push($this->authors, $author);
        return $this;
    }

    public function addAccess($flag)
    {
        array_push($this->accesses, $flag);
        return $this;
    }

    public function addController(Controller $controller)
    {
        array_push($this->controllers, $controller);
        return $this;
    }
}