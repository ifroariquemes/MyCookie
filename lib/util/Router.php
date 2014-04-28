<?php

namespace lib\util;

abstract class Router {

  public function Route() {
    global $_MyCookie;    
    global $_Async;
    I18n::singleton();
    $_Async = strpos(filter_input(INPUT_SERVER, 'REQUEST_URI'), 'async') > 0;
    if ($_MyCookie->getGateway() == $_MyCookie->getModule())
      $this->ShowPage();
    else
      $this->ShowPage($_MyCookie->requestAction($_MyCookie->getControlClass(), $_MyCookie->getAction()));
  }

  public abstract function ShowPage($view = null);
}

?>
