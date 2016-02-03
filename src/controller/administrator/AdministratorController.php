<?php

namespace controller\administrator;

use lib\util\Router;
use controller\user\UserController;
use model\user\accountType\AccountType;

class AdministratorController extends Router
{
    private $userControl;

    public function __construct()
    {
        $this->userControl = new UserController();
    }

    public static function checkUserLoggedIn()
    {
        global $_Config;
        if ($_Config->allowPublicSignup) {
            if (!UserController::isUserLoggedIn()) {
                AdministratorController::showLogin();
                exit;
            }
        } else if (!UserController::isAdministratorLoggedIn()) {
            AdministratorController::showLogin();
            exit;
        }
    }

    public static function showLogin()
    {
        global $_MyCookie;
        global $_Cache;
        $account = AccountType::select('a')->getQuery()->execute();
        if (count($account) === 0) {
            UserController::firstRun();
        }
        $_Cache->doCache = false;
        ob_start();
        $_MyCookie->loadView('administrator', 'login');
        $view = ob_get_contents();
        ob_end_clean();
        $_Cache->doCache($view);
        echo $view;
        $_SESSION[\lib\MyCookie::MESSAGE_SESSION] = '';
    }

    public function showPage($view = null, $ajax = false)
    {
        global $_MyCookie;
        global $_Cache;
        $this->checkUserLoggedIn();
        if (is_null($view)) {
            ob_start();
            $_MyCookie->loadView('administrator', 'Main');
            $view = ob_get_contents();
            ob_end_clean();
        }
        if ($ajax) {
            $_Cache->doCache($view);
            echo $view;
        } else {
            ob_start();
            $_MyCookie->loadTemplate('administrator', 'Template', $view);
            $page = ob_get_contents();
            ob_end_clean();
            $_Cache->doCache($page);
            echo $page;
        }
    }
}