<?php

namespace lib\util;

class Translation
{
    const LANG_PATH = 'src/lang';

    private $files;
    private $lang;
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function __construct()
    {
        global $_MyCookie;
        global $_Config;
        $this->files = array();
        $this->lang = $_Config->lang;
        if ($this->lang == 'user') {
            $this->lang = $_MyCookie->getUserLanguage();
        }
    }

    public function loadTranslation($module)
    {
        if (!isset($this->files['dev'][$module])) {
            $dPath = sprintf('%s/%s/%s.json', self::LANG_PATH, 'dev', $module);
            $lPath = sprintf('%s/%s/%s.json'
                    , self::LANG_PATH, $this->lang, $module);
            if (file_exists($dPath)) {
                $dContent = file_get_contents($dPath);
                $this->files['dev'][$module] = json_decode($dContent);
                if (file_exists($lPath)) {
                    $lContent = file_get_contents($lPath);
                    $this->files[$this->lang][$module] = json_decode($lContent);
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function getTranslation($module, $key)
    {
        if (isset($this->files['dev'][$module])) {
            $key = str_replace('.', '->', $key);
            if (isset($this->files[$this->lang][$module])) {
                $t = eval("return @\$this->files[\$this->lang][\$module]->$key;");
                if (!is_null($t)) {
                    return $t;
                }
            }
            if (isset($this->files[$this->lang][$module])) {
                $t = eval("return @\$this->files['dev'][\$module]->$key;");
                if (!is_null($t)) {
                    return $t;
                }
            } 
            return $key;
        } else if ($this->loadTranslation($module)) {
            return $this->getTranslation($module, $key);
        } else {
            return $key;
        }
    }
}