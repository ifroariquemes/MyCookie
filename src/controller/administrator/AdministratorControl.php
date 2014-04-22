<?php

namespace controller\administrator;

use lib;
use lib\MyCookie;
use controller\user\UserControl;

class AdministratorControl extends lib\MyCookieRouter {

    private $userControl;

    public function __construct() {
        $this->userControl = new UserControl();
    }

    public static function VerifyAdministratorLoggedIn() {
        if (!UserControl::isAdministratorLoggedIn()) {
            AdministratorControl::ShowLogin();
            exit;
        }
    }

    public static function ShowLogin() {
        /* @var $_MyCookie MyCookie */
        global $_MyCookie;
        $_MyCookie->LoadView('administrator', 'Login');        
        //unset($_SESSION[MyCookie::MessageSession]);
    }

    public function ShowPage($view = null, $ajax = false) {
        global $_Cache;
        $this->VerifyAdministratorLoggedIn();
        if (is_null($view)) {
            ob_start();
            include('administrador.view.principal.php');
            $view = ob_get_contents();
            ob_end_clean();
        }
        if ($ajax) {
            $_Cache->doCache($view);
            echo $view;
        } else {
            ob_start();
            include('administrador.tmpl.padrao.php');
            $page = ob_get_contents();
            ob_end_clean();
            $_Cache->doCache($page);
            echo $page;
        }
    }

    public static function CabecalhoModulo($nomeModulo, $voltarPara) {

        include('administrador.view.cabecalho.php');
    }

}

?>
