<?php

namespace controller\administrator\menu;

use model\administrator\menu\Menu;

class MenuControl
{

    public static function listCurrentModuleName()
    {
        global $_MyCookie;
        return MenuControl::loadMenu($_MyCookie->getModule(), false)->getName();
    }

    public static function listModuleNames()
    {
        global $_MyCookie;
        $data = MenuControl::loadMenus();
        $_MyCookie->loadView('administrator/menu', 'ListNames', $data);
    }

    public static function listModuleIcons()
    {
        global $_MyCookie;
        $data = MenuControl::loadMenus();
        $_MyCookie->loadView('administrator/menu', 'listIcons', $data);
    }

    private static function loadMenus()
    {
        $menuList = array();
        $menuListRet = array();
        $pathNotAllowed = array(
            '.', '..',
            'administrator.php', 'index.php', 'build.php');
        $hDiretorio = opendir('src/config');
        while ($hModule = readdir($hDiretorio)) {
            if (!in_array($hModule, $pathNotAllowed)) {
                if (($menuOption = MenuControl::loadMenu($hModule)) !== false) {
                    array_push($menuList, $menuOption);
                    array_push($menuListRet, $hModule);
                }
            }
        }
        array_multisort($menuListRet, $menuList);
        return $menuList;
    }

    private static function loadMenu($mConfigFile, $checkAccessLevel = true)
    {
        global $_MyCookie;
        $mPathName = explode('.', $mConfigFile)[0];
        $mConfig = $_MyCookie->getModuleConfiguration($mPathName);
        if (empty($mConfig->getName())) {
            throw new \Exception("The $mPathName module needs a name.");
        }
        return (MenuControl::checkAccessLevel($mConfig->getAccesses()) ||
                !$checkAccessLevel) ?
                new Menu($mPathName, $mConfig) :
                false;
    }

    private static function checkAccessLevel($accesses)
    {
        global $_User;        
        return (empty($accesses) ||
                in_array($_User->getAccountType()->getFlag(), $accesses));
    }

    public static function getBackLink()
    {
        $httpReferer = filter_input(INPUT_SERVER, 'HTTP_REFERER');
        $requestURI = filter_input(INPUT_SERVER, 'REQUEST_URI');
        return (strpos($httpReferer, $requestURI) === false) ?
                $httpReferer :
                '..';
    }
}