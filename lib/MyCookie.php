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
class MyCookie {

    const UserIdSession = 'MYCOOKIE_USER_SESSION';
    const MessageSession = 'MYCOOKIE_MESSAGE_SESSION';
    const AdmModules = 'menu';

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
    public static function singleton() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    /**
     * Build the rules for what execute based on URL
     * @global util\Cache $_Cache     
     */
    public function __construct() {
        $this->CheckCache();
        global $_EntityManager;
        $_EntityManager = util\Database::EntityManager();
        UserController::LoadSessionUser();
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
            $this->DefaultAction();
        }
    }

    private function setSubModuleCustom() {
        if (array_key_exists(1, $this->URLVariables) && $this->URLVariables[1] != $this->action) {
            $this->submodule = $this->URLVariables[1];
        }
        if (!empty($this->submodule)) {
            $this->namespace .= "\\$this->submodule";
        }
    }

    private function setActionCustom() {
        $moduleConfig = $this->getModuleConfiguration($this->module);
        $this->VerifyModuleConfigurationIntegrity($moduleConfig);
        if ($this->getURLVariablesLength() === 1 || $this->module == 'administrador') {
            $this->action = strval($moduleConfig->getHome()->getAction());
        } else {
            $this->action = end($this->URLVariables);
        }
    }

    private function setModuleCustom() {
        if ($this->getURLVariablesLength() > 1 && $this->URLVariables[0] == 'administrator') {
            $this->module = $this->URLVariables[1];
        } else {
            $this->module = $this->URLVariables[0];
        }
    }

    private function getURLVariablesLength() {
        return count($this->URLVariables);
    }

    private function setURLVariables() {
        $remove = str_replace('/index.php', '', filter_input(INPUT_SERVER, 'SCRIPT_NAME'));
        $this->URLVariables = explode('/', str_replace($remove, '', filter_input(INPUT_SERVER, 'REDIRECT_URL')));
        if ($this->URLVariables[0] == '') {
            array_shift($this->URLVariables);
        }
        if (end($this->URLVariables) == '') {
            array_pop($this->URLVariables);
        }
    }

    private function setGateway() {
        if ($this->getURLVariablesLength() > 0 && $this->URLVariables[0] == 'administrator') {
            $this->gateway = 'administrator';
        } else {
            $this->gateway = 'index';
        }
    }

    private function CheckCache() {
        global $_Cache;
        $_Cache = util\Cache::getInstance();
        $_Cache->getCache();
    }

    private function DefaultAction() {
        $MyCookieConfiguration = $this->getMyCookieConfiguration();
        $this->gateway = $MyCookieConfiguration->home->gateway;
        $this->module = $MyCookieConfiguration->home->module;
        $this->action = $MyCookieConfiguration->home->action;
        $this->namespace = "modules\\{$this->module}";
        $this->controlClass = $this->namespace . '\\' . $MyCookieConfiguration->home->control;
    }

    public function getSOServidor() {
        if (empty($this->serverOS)) {
            $soServidor = filter_input(INPUT_SERVER, 'SERVER_SIGNATURE');
            if (strpos($soServidor, 'Unix') !== false) {
                $this->serverOS = 'Linux';
            } else if (strpos($soServidor, 'Win32') !== false) {
                $this->serverOS = 'Windows';
            } else {
                $this->serverOS = 'Mac';
            }
        }
        return $this->serverOS;
    }

    public function getGateway() {
        return $this->gateway;
    }

    public function getGatewayClass() {
        return $this->getControlClass($this->gateway);
    }

    /**
     * Retorna a acao princial executada pelo MyCookie
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Retorna classe de controle principal executada no MyCookie
     * @return string
     */
    public function getControlClass($module = null, $submodule = '') {
        if (is_null($module)) {
            if (empty($this->controlClass)) {
                $moduleConfiguration = $this->getModuleConfiguration($this->module);
                $this->VerifyModuleConfigurationIntegrity($moduleConfiguration);
                foreach ($moduleConfiguration->getControllers() as $controller) {
                    if ($this->submodule == $controller->getSubmodule()) {
                        return $this->controlClass = "$this->namespace\\{$controller->getName()}";
                    }
                }
            }
            return $this->controlClass;
        } else {
            $moduleConfiguration = $this->getModuleConfiguration($module);
            $this->VerifyModuleConfigurationIntegrity($moduleConfiguration);
            $namespace = "controller\\$module";
            foreach ($moduleConfiguration->getControllers() as $controller) {
                if ($submodule == $controller->getSubmodule()) {
                    return (empty($submodule)) ? "$namespace\\{$controller->getName()}" : "$namespace\\$submodule\\{$controller->getName()})";
                }
            }
        }

        //throw new \Exception("There is something wrong with module $module configuration.");
    }

    public function VerifyModuleConfigurationIntegrity($moduleConfiguration) {
        if (is_null($moduleConfiguration)) {
            throw new \Exception("There is something wrong with $this->module module configuration.");
        }
    }

    public function getMyCookieConfiguration() {
        if (empty($this->configuration)) {
            $this->configuration = json_decode(file_get_contents('config.json'));
        }
        return $this->configuration;
    }

    /**
     * Retorna um objeto com a configuracao de um modulo
     * @param string $module Nome do modulo
     * @return util\module\Module
     */
    public function getModuleConfiguration($module) {
        $module = strtolower($module);
        $config = str_replace(array('<?php', '?>'), '', file_get_contents("src/config/$module.php"));
        return eval($config);
    }

    /**
     * Retorna o (nome da pasta) modulo principal executado pelo MyCookie
     * @return string
     */
    public function getModule($namespace = null) {
        if (is_null($namespace)) {
            return $this->module;
        } else {
            $arrayNamespace = explode('\\', $namespace);
            return $arrayNamespace[1];
        }
    }

    /**
     * Retorna o modulo auxiliar principal (o ultimo de uma serie de submodulos, responsavel pela acao) executado pelo MyCookie
     * @return string
     */
    public function getAuxiliarModule() {
        return $this->auxiliarModule;
    }

    /**
     * Retorno o namespace do modulo (ou modulo auxiliar se houver)
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * Retorna o nome do modulo principal executado pelo MyCookie segundo o arquivo de configuracao XML do modulo
     * @return string
     */
    public function getModuleName() {
        $moduleConfiguration = $this->getModuleConfiguration($this->module);
        return $moduleConfiguration->name;
    }

    public function getAuxiliarModuleName() {
        $moduleConfiguration = $this->getModuleConfiguration($this->getAuxiliarModule());
        return $moduleConfiguration->name;
    }

    /**
     * The main URL of website (w/o variables and modules)
     * @return string
     */
    public function getSite() {
        if (empty($this->site)) {
            $requestScheme = filter_input(INPUT_SERVER, 'REQUEST_SCHEME');
            $httpHost = filter_input(INPUT_SERVER, 'HTTP_HOST');
            $scriptName = str_replace('index.php', '', filter_input(INPUT_SERVER, 'SCRIPT_NAME'));
            $this->site = sprintf('%s://%s%s', $requestScheme, $httpHost, $scriptName);
        }
        return $this->site;
    }

    public function getSubmodule() {
        return $this->submodule;
    }

    public function getURLVariables($index = null) {
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
    public function image($src, $title, $alt = '') {
        $image = '<img title="%s" alt="%s" src="%s%s">';
        echo sprintf($image, $title, $alt, $this->getSite(), $src);
    }

    /**
     * Exibe um link na tela configurando-o segundo as exigencias do MyCookie
     * @param type $text Texto do link
     * @param type $alt Texto alternativo para o link
     * @param type $module Nome do modulo
     * @param type $_ [opcional] Nome do(s) submodulo(s) e/ou acao
     */
    public function link($text, $alt, $href) {
        $link = '<a href="%s%s" alt="%s">%s</a>';
        echo sprintf($link, $this->getSite(), $href, $alt, $text);
    }

    public function mountLink($module, $_ = null) {
        $dir = func_get_args();
        $link = $this->getSite();
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
    public function sortObjects($Objects, $attribute, $sort = SORT_ASC) {
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

    public function requestAction($class, $action) {
        if (!class_exists($class)) {
            $class = $this->getControlClass($this->URLVariables[0]);
            if (empty($class)) {
                throw new Exception("There is no default control class setted to $this->gateway");
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

    public function LoadView($module, $view, $data = null) {
        include("src/view/$module/$view.php");
    }

    public function LoadTemplate($module, $template, $view) {
        include("src/view/$module/$template.php");
    }

    public function CSSBundle() {
        $cssBundle = '<link rel="stylesheet" type="text/css" href="%scomponents/bundle.css" />';
        echo sprintf($cssBundle, $this->getSite());
    }
    
    public function RequireJS() {
        $scriptRequireJS = '<script type="text/javascript" src="%scomponents/require.js"></script>';
        echo sprintf($scriptRequireJS, $this->getSite());
    }

    public function JSBundle() {                
        $scriptBundle = '<script type="text/javascript" src="%scomponents/bundle.js"></script>';        
        include_once('components/mycookie.js.php');        
        echo sprintf($scriptBundle, $this->getSite());        
    }

}
