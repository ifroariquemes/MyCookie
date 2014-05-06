<?php

namespace controller\index;

use lib;

class IndexController extends lib\util\Router {

    public function ShowPage($view = null) {
        global $_Cache;
        global $_Async;
        if (is_null($view)) {
            ob_start();
            include('src/view/index/main.php');
            $view = ob_get_contents();
            ob_end_clean();
            $_Cache->doCache = false;
        }
        if ($_Async) {
            $_Cache->doCache($view);
            echo $view;
        } else {
            ob_start();
            include('src/view/index/template.php');
            $page = ob_get_contents();
            ob_end_clean();
            $_Cache->doCache($page);
            echo $page;
        }
    }

}

?>
