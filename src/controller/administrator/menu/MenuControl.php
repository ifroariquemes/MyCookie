<?php

namespace controller\administrator\menu;

use model\administrator\menu\Menu;

class MenuControl {

    public static function ListNames() {
        $menuList = MenuControl::LoadMenus();
        include('menu.view.listanomes.php');
    }

    public static function ListIcons() {
        $menuList = MenuControl::LoadMenus();
        include('src/view/administrator/menu/ListIcons.php');
    }

    private static function LoadMenus() {
        $menuList = array();
        $menuListRet = array();
        $hDiretorio = opendir('modules');
        while ($hModule = readdir($hDiretorio)) {
            if (!in_array($hModule, array('.', '..', 'administrator.json', 'index.json', 'build.json'))) {
                if (($menuOption = MenuControl::LoadMenu($hModule)) !== false) {
                    array_push($menuList, $menuOption);
                    array_push($menuListRet, $hModule);
                }
            }
        }
        array_multisort($menuListRet, $menuList);
        return $menuList;
    }

    private static function LoadMenu($module) {        
        $moduleConfig = json_decode(file_get_contents("modules/$module"));
        $moduleName = explode('.', $module)[0];
        if (!in_array('name', array_keys(get_object_vars($moduleConfig)))) {
            throw new \Exception("The module at $module needs at least a name.");
        }
        return (MenuControl::VerifyAccessLevel(@$moduleConfig->access)) ? new Menu($moduleConfig->name, $moduleName, @$moduleConfig->tile->icon, @$moduleConfig->tile->color) : false;
    }    

    public static function mountLinkOption($moduleName, TMenuOpcao $opcao) {

        global $_Biscoito;

        $opcaoFormat = '<li class="%s"><a href="%s" onclick="%s">%s</a></li>';

        $urlFormat = '%sadministrador/%s/%s/';

        $popupFormat = "_Biscoito.AbrirPopup('Frm%s',700,'%s');";

        $classePadrao = 'icn_novo_artigo';

        $popup = '';

        $moduleName = strtolower(Util\TTexto::RemoverAcentos($moduleName));

        $nomeOpcao = strtolower(Util\TTexto::RemoverAcentos($opcao->getName()));

        $icone = (!$opcao->getIcon()) ? $classePadrao : "icn_{$opcao->getIcon()}";

        if ($opcao->AbrirPopup()) {

            $popup = sprintf($popupFormat, $moduleName, $opcao->getURL());

            $url = '#';
        } else
            $url = (empty($opcao->getURL)) ?
                    sprintf($urlFormat, $_Biscoito->getSite(), $moduleName, $nomeOpcao) :
                    sprintf($urlFormat, $_Biscoito->getSite(), $opcao->getURL(), '');

        echo sprintf($opcaoFormat, $icone, $url, $popup, $opcao->getName());
    }

    private static function VerifyAccessLevel($level) {
        global $_MyCookieUser;
        $level = trim($level);
        $level = str_replace("\n", '|', $level);
        $level = str_replace(" ", '', $level);
        if (empty($level))
            return true;
        $level = explode("|", $level);
        return (in_array($_MyCookieUser->getAccountType()->getFlag(), $level));
    }

}

?>