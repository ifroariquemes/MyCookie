<?php

namespace controller\administrator;

use lib\util\Router;
use controller\user\UserController;
use model\user\accountType\AccountType;

class AdministratorController extends Router {

    private $userControl;

    public function __construct() {
        $this->userControl = new UserController();
    }

    public static function VerifyAdministratorLoggedIn() {
        if (!UserController::isAdministratorLoggedIn()) {
            AdministratorController::ShowLogin();
            exit;
        }
    }

    public static function ShowLogin() {
        /* @var $_MyCookie \lib\MyCookie */
        global $_MyCookie;
        $account = AccountType::select('a')->getQuery()->execute();
        if (count($account) === 0) {
            UserController::firstRun();             
        }
        $_MyCookie->LoadView('administrator', 'Login');
        //unset($_SESSION[MyCookie::MessageSession]);
    }

    public function ShowPage($view = null, $ajax = false) {
        /* @var $_MyCookie \lib\MyCookie */
        global $_MyCookie;
        /* @var $_Cache \lib\util\Cache */
        global $_Cache;
        $this->VerifyAdministratorLoggedIn();
        if (is_null($view)) {
            ob_start();
            $_MyCookie->LoadView('administrator', 'Main');            
            $view = ob_get_contents();
            ob_end_clean();
        }
        if ($ajax) {
            $_Cache->doCache($view);
            echo $view;
        } else {
            ob_start();
            $_MyCookie->LoadTemplate('administrator', 'Template', $view);            
            $page = ob_get_contents();
            ob_end_clean();
            $_Cache->doCache($page);
            echo $page;
        }
    }

    public static function ModuleHeader($nomeModulo, $voltarPara) {

        include('administrador.view.cabecalho.php');
    }

}

?>
