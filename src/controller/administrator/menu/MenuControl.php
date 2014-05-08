<?php

namespace controller\administrator\menu;

use model\administrator\menu\Menu;

class MenuControl {

    public static function ListCurrentModuleName() {
        global $_MyCookie;
        return MenuControl::LoadMenu("{$_MyCookie->getModule()}.json")->getName();
    }

    public static function ListModuleNames() {
        global $_MyCookie;
        $data = MenuControl::LoadMenus();
        $_MyCookie->LoadView('administrator/menu', 'ListNames', $data);
    }

    public static function ListModuleIcons() {
        global $_MyCookie;
        $data = MenuControl::LoadMenus();
        $_MyCookie->LoadView('administrator/menu', 'ListIcons', $data);
    }

    private static function LoadMenus() {
        $menuList = array();
        $menuListRet = array();
        $hDiretorio = opendir('src/config');
        while ($hModule = readdir($hDiretorio)) {
            if (!in_array($hModule, array('.', '..', 'administrator.php', 'index.php', 'build.php'))) {
                if (($menuOption = MenuControl::LoadMenu($hModule)) !== false) {
                    array_push($menuList, $menuOption);
                    array_push($menuListRet, $hModule);
                }
            }
        }
        array_multisort($menuListRet, $menuList);
        return $menuList;
    }

    private static function LoadMenu($moduleConfigFile) {        
        global $_MyCookie;
        $modulePathName = explode('.',$moduleConfigFile)[0];
        $moduleConfig = $_MyCookie->getModuleConfiguration($modulePathName);                
        if (empty($moduleConfig->getName())) {
            throw new \Exception("The $modulePathName module needs a name.");
        }
        return (MenuControl::VerifyAccessLevel($moduleConfig->getAccesses())) ? new Menu($modulePathName, $moduleConfig) : false;
    }

    private static function VerifyAccessLevel($accesses) {
        global $_MyCookieUser;                    
        return (empty($accesses) || in_array($_MyCookieUser->getAccountType()->getFlag(), $accesses));
    }

}

?>