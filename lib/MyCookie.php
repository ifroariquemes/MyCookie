<?php

namespace lib;

use lib\util;
use controller\user\UserController;

/**
 * @author Natanael Simoes
 * @category Framework
 * @package lib 
 * @uses lib\util
 * @version 1.0
 */
class MyCookie
{
    const USER_ID_SESSION = 'MYCOOKIE_USER_SESSION';
    const MESSAGE_SESSION = 'MYCOOKIE_MESSAGE_SESSION';

    /**
     * The module's namespace
     * @var string
     */
    private $namespace;

    /**
     * The module
     * @var string
     */
    private $module;

    /**
     * Array containing variables in URL
     * @var mixed
     */
    private $URLVariables;

    /**
     * The submodule (if running)
     * @var string
     */
    private $submodule;

    /**
     * The auxiliar module (if running)
     * @var string
     */
    private $auxiliarModule;

    /**
     * The control class
     * @var string
     */
    private $controlClass;

    /**
     * The action executed by module
     * @var string
     */
    private $action;

    /**
     * The gateway where the output will be displayed
     * @var string
     */
    private $gateway;

    /**
     * The OS from server
     * @var type 
     */
    private $serverOS;

    /**
     * The base site running MyCookie
     * @var string 
     */
    private $site;

    /**
     * MyCookie configuration
     * @var mixed
     */
    private $configuration;

    /**
     * The instance for singleton
     * @var MyCookie 
     */
    private static $instance;

    /**
     * The singleton method
     * @return MyCookie
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            session_start();
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Build the rules for what execute based on URL      
     */
    public function __construct()
    {
        global $_Server;
        global $_EntityManager;
        $this->restoreCache();
        $this->loadBaseURL();
        $this->loadConfiguration();
        $_Server = util\Server::getInstance();
        $_EntityManager = util\Database::EntityManager();
        UserController::loadSessionUser();
        $this->setURLVariables();
        $this->setGateway();
        if ($this->getURLVariablesLength() > 0) {
            $this->setModuleCustom();
            if ($this->gateway == 'administrator') {
                $this->URLVariables = array_slice($this->URLVariables, 1);
            }
            $this->setActionCustom();
            $this->namespace = sprintf('controller\\%s', $this->module);
            $this->setSubModuleCustom();
        } else {
            $this->defaultAction();
        }
    }

    private function setSubModuleCustom()
    {
        if (array_key_exists(1, $this->URLVariables) &&
                $this->URLVariables[1] != $this->action) {
            $this->submodule = $this->URLVariables[1];
        }
        if (!empty($this->submodule)) {
            $this->namespace .= "\\$this->submodule";
        }
    }

    private function setActionCustom()
    {
        $moduleConfig = $this->getModuleConfiguration($this->module);
        $this->checkModuleConfigurationIntegrity($moduleConfig);
        if ($this->getURLVariablesLength() === 1 ||
                $this->module == 'administrador') {
            $this->action = strval($moduleConfig->getHome()->getAction());
        } else {
            $this->action = end($this->URLVariables);
        }
    }

    private function setModuleCustom()
    {
        if ($this->getURLVariablesLength() > 1 &&
                $this->URLVariables[0] == 'administrator') {
            $this->module = $this->URLVariables[1];
        } else {
            $this->module = $this->URLVariables[0];
        }
    }

    private function getURLVariablesLength()
    {
        return count($this->URLVariables);
    }

    private function setURLVariables()
    {
        global $_Server;
        $remove = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        if (isset($_SERVER['REDIRECT_URL'])) {
            $this->URLVariables = explode('/'
                    , str_replace($remove, '', $_SERVER['REDIRECT_URL']));
        } else {
            $this->URLVariables = explode('/'
                    , str_replace($remove, '', $_SERVER['REQUEST_URI']));
        }
        if ($this->URLVariables[0] == '') {
            array_shift($this->URLVariables);
        }
        if (end($this->URLVariables) == '') {
            array_pop($this->URLVariables);
        }
    }

    private function setGateway()
    {
        if ($this->getURLVariablesLength() > 0 &&
                $this->URLVariables[0] == 'administrator') {
            $this->gateway = 'administrator';
        } else {
            $this->gateway = 'index';
        }
    }

    private function restoreCache()
    {
        global $_Cache;
        $_Cache = util\Cache::getInstance();
        $_Cache->getCache();
    }

    private function defaultAction()
    {
        global $_Config;
        $this->gateway = $_Config->home->gateway;
        $this->module = $_Config->home->module;
        $this->action = $_Config->home->action;
        $this->namespace = "modules\\{$this->module}";
        $this->controlClass = $this->namespace . '\\' . $_Config->home->control;
    }

    public function getGateway()
    {
        return $this->gateway;
    }

    public function getGatewayClass()
    {
        return $this->getControlClass($this->gateway);
    }

    /**
     * Retorna a acao princial executada pelo MyCookie
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Retorna classe de controle principal executada no MyCookie
     * @return string
     */
    public function getControlClass($module = null, $submodule = '')
    {
        if (is_null($module)) {
            if (empty($this->controlClass)) {
                $mc = $this->getModuleConfiguration($this->module);
                foreach ($mc->getControllers() as $c) {
                    if ($this->submodule == $c->getSubmodule()) {
                        return $this->controlClass = "$this->namespace\\"
                                . "{$c->getName()}";
                    }
                }
            }
            return $this->controlClass;
        } else {
            $mc = $this->getModuleConfiguration($module);
            $this->checkModuleConfigurationIntegrity($mc);
            $namespace = "controller\\$module";
            foreach ($mc->getControllers() as $c) {
                if ($submodule == $c->getSubmodule()) {
                    return (empty($submodule)) ?
                            "$namespace\\{$c->getName()}" :
                            "$namespace\\$submodule\\{$c->getName()})";
                }
            }
        }
    }

    public function checkModuleConfigurationIntegrity($mc)
    {
        if (is_null($mc)) {
            throw new
            \Exception("There is something wrong with $this->module module "
            . "configuration.");
        }
    }

    private function loadConfiguration()
    {
        global $_Config;
        $_Config = json_decode(file_get_contents('config.json'));
        return $_Config;
    }

    /**
     * Retorna um objeto com a configuracao de um modulo
     * @param string $module Nome do modulo
     * @return util\module\Module
     */
    public function getModuleConfiguration($module)
    {
        $module = strtolower($module);
        $config = str_replace(array('<?php', '?>'), ''
                , file_get_contents("src/config/$module.php"));
        $moduleConfiguration = eval($config);
        $this->checkModuleConfigurationIntegrity($moduleConfiguration);
        return $moduleConfiguration;
    }

    /**
     * Retorna o (nome da pasta) modulo principal executado pelo MyCookie
     * @return string
     */
    public function getModule($namespace = null)
    {
        if (is_null($namespace)) {
            return $this->module;
        } else {
            $arrayNamespace = explode('\\', $namespace);
            return $arrayNamespace[1];
        }
    }

    /**
     * Retorna o modulo auxiliar principal (o ultimo de uma serie de submodulos, 
     * responsavel pela acao) executado pelo MyCookie
     * @return string
     */
    public function getAuxiliarModule()
    {
        return $this->auxiliarModule;
    }

    /**
     * Retorno o namespace do modulo (ou modulo auxiliar se houver)
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Retorna o nome do modulo principal executado pelo MyCookie segundo o 
     * arquivo de configuracao XML do modulo
     * @return string
     */
    public function getModuleName()
    {
        $mc = $this->getModuleConfiguration($this->module);
        return $mc->name;
    }

    public function getAuxiliarModuleName()
    {
        $mc = $this->getModuleConfiguration($this->getAuxiliarModule());
        return $mc->name;
    }

    /**
     * The main URL of website (w/o variables and modules)
     * @return string
     */
    private function loadBaseURL()
    {
        global $_BaseURL;
        if ((!empty($_SERVER['HTTPS']) &&
                $_SERVER['HTTPS'] !== 'off') ||
                $_SERVER['SERVER_PORT'] == 443) {
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $_BaseURL = sprintf('%s://%s%s', $protocol, $host, $scriptName);
        return $_BaseURL;
    }

    public function getSubmodule()
    {
        return $this->submodule;
    }

    public function getURLVariables($index = null)
    {
        if (is_null($index)) {
            return $this->URLVariables;
        } else if (array_key_exists($index, $this->URLVariables)) {
            return $this->URLVariables[$index];
        }
        return null;
    }

    /**
     * Exibe uma imagem na tela configurando-a seguindo as exigencias do MyCookie
     * @param string $src Caminho relativo da imagem a partir da raiz do site
     * @param string $title Titulo da imagem
     * @param string $alt Texto alternativo a imagem
     */
    public function image($src, $class = '', $title = '', $alt = '')
    {
        global $_BaseURL;
        $image = '<img class="%s" title="%s" alt="%s" src="%s%s">';
        echo sprintf($image, $class, $title, $alt, $_BaseURL, $src);
    }

    /**
     * Exibe um link na tela configurando-o segundo as exigencias do MyCookie
     * @param type $text Texto do link
     * @param type $alt Texto alternativo para o link
     * @param type $module Nome do modulo
     * @param type $_ [opcional] Nome do(s) submodulo(s) e/ou acao
     */
    public function link($text, $alt, $href)
    {
        global $_BaseURL;
        $link = '<a href="%s%s" alt="%s">%s</a>';
        echo sprintf($link, $_BaseURL, $href, $alt, $text);
    }

    public function mountLink($module, $_ = null)
    {
        global $_BaseURL;
        $dir = func_get_args();
        $link = $_BaseURL;
        foreach ($dir as $part) {
            $link.= "$part/";
        }
        return $link;
    }

    /**
     * Ordena os objetos dentro de uma colecao (array)
     * @param array $Objects Objetos Colecao de objetos
     * @param string $attribute Nome do atributo que regera a ordenacao
     * @param integer $sort Metodo de ordenacao da colecao: SORT_ASC ou SORT_DESC
     * @return array
     */
    public function sortObjects($Objects, $attribute, $sort = SORT_ASC)
    {
        $solutionFormat = 'return (strtolower($arrayObjetos[$j+1]->get%s()) %s strtolower($arrayObjetos[$j]->get%s()));';
        $solutionString = sprintf($solutionFormat, $attribute, $sort == SORT_ASC ? '<' : '>', $attribute);
        for ($i = 0, $objectsLength = count($Objects); $i < $objectsLength; $i++) {
            for ($j = 0; $j < $objectsLength - 1; $j++) {
                $theSolution = eval($solutionString);
                if ($theSolution) {
                    $tempVar = $Objects[$j];
                    $Objects[$j] = $Objects[$j + 1];
                    $Objects[$j + 1] = $tempVar;
                }
            }
        }
        return $Objects;
    }

    public function requestAction($class, $action)
    {
        if (!class_exists($class)) {
            $class = $this->getControlClass($this->URLVariables[0]);
            if (empty($class)) {
                throw new \Exception("There is no default control class setted to $this->gateway");
            }
        }
        $vector = new util\Vector(get_class_methods($class));
        if ($this->getURLVariablesLength() > 1) {
            $possibleAction = str_replace('_', '', $this->URLVariables[1]);
            if (count($vector->Procurar($possibleAction)) > 0) {
                $this->action = $action = $possibleAction;
            }
        }
        ob_start();
        $object = new $class;
        $object->$action();
        $request = ob_get_contents();
        ob_end_clean();
        return $request;
    }

    public function loadView($module, $view, $data = null, $return = false)
    {
        global $_MyCookie;
        if ($return) {
            ob_start();
            include("src/view/$module/$view.php");
            $returning = ob_get_contents();
            ob_end_clean();
            return $returning;
        } else {
            include("src/view/$module/$view.php");
        }
    }

    public function loadTemplate($module, $template, $view)
    {
        include("src/view/$module/$template.php");
    }

    public function useScript($relativePath)
    {
        global $_BaseURL;
        $absolutePath = '<script type="text/javascript" src="%s%s"></script>';
        echo sprintf($absolutePath, $_BaseURL, $relativePath);
    }

    public function useStyle($relativePath)
    {
        global $_BaseURL;
        $absolutePath = '<link rel="stylesheet" type="text/css" href="%s%s" />';
        echo sprintf($absolutePath, $_BaseURL, $relativePath);
    }

    public function CSSBundle()
    {
        global $_Config;
        if ($_Config->build->production) {
            $this->useStyle('components/bundle.css');
        } else {
            $bCtrl = new \controller\build\BuildController;
            $bCtrl->createBuildCSS(true);
            foreach ($bCtrl->getCSSFiles() as $css) {
                $this->useStyle($css);
            }
        }
    }

    public function RequireJS()
    {
        $this->useScript('components/require.js');
    }

    public function JSBundle()
    {
        global $_Config;
        if ($_Config->build->production) {
            $this->useScript('components/bundle.js');
        } else {
            $bCtrl = new \controller\build\BuildController;
            $bCtrl->createBuildJS(true);
            foreach ($bCtrl->getJSFiles() as $js) {
                $this->useScript("{$js}.js");
            }
        }
    }

    public function goBackTo($module, $_ = null)
    {
        global $_BaseURL;
        global $_MyCookieGoBack;
        $_MyCookieGoBack = $_BaseURL;
        foreach (func_get_args() as $href) {
            $_MyCookieGoBack .= "$href/";
        }
    }

    public function getUserLanguage()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lc = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        return $lc;
    }

    public function getTranslation($module, $key)
    {
        return util\Translation::getInstance()->getTranslation($module, $key);
    }
}