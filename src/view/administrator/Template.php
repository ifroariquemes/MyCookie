<?php

use Biscoito\Modulos\Administrador\Breadcrumbs;
use Biscoito\Modulos\Usuario;

/* @var $_MyCookie \lib\MyCookie */
global $_MyCookie;
/* @var $_MyCookieUser model\user\User */
global $_MyCookieUser;
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="pt-BR"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="pt-BR"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="pt-BR"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="pt-BR"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />        
        <meta name="viewport" content="width=device-width">
        <title>Biscoito - Painel Administrativo</title>                        
        <?php $_MyCookie->CSSBundle() ?>
        <!--@RenderSection("head", false)-->
    </head>
    <body>        
        <header class="container-fluid">
            <div class="row">
                <nav class="navbar navbar-default navbar-static-top" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Brand</a>
                    </div>
                    <div class="collapse navbar-collapse navbar-ex1-collapse">                    
                        <ul class="nav navbar-nav navbar-right">                        
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav>
            </div>
        </header>
        <?php if ($_MyCookie->getModule() == 'administrator') : ?>
            <header id="nav-bar" class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Painel Administrativo</h1>
                        <a id="user-info" class="pull-right" href="#">
                            <div class="user-info-block">
                                <h3><?php echo $_MyCookieUser->getName() ?></h3>
                                <h4><?php echo $_MyCookieUser->getLastName() ?></h4>
                            </div>
                            <div class="user-info-block">
                                <b class="icon-user"></b>
                            </div>
                        </a>
                    </div>
                </div>                
            </header>
        <?php endif; ?>
        <div id="charms" class="win-ui-dark" style="z-index: 1">
            <div id="charms-header" class="row">
                <div class="col-lg-1 align-left">
                    <a id="close-charms" class="win-backbutton" href="#"></a>
                </div>                
            </div>
            <div id="charms-body">
                <div id="charms-user" class="span3">
                    <br />
                    <div class="row">
                        <div class="span2">
                            <h3><?php echo $_MyCookieUser->getName() ?></h3>
                            <h4><?php echo $_MyCookieUser->getLastName() ?></h4>
                        </div>
                        <div class="span1">
                            <img src="http://www.gravatar.com/avatar/6cc5a644d49a9bfe88bc0819ae7bdea6.png?s=48" alt="user" />
                        </div>
                    </div>
                    <hr/>
                    <div>
                        <div class="btn btn-large" onclick="_Biscoito.IrPara('usuario/logout');"><i class="icon-out"></i> Sair</div>                                                                   
                    </div>
                </div>
                <div id="charms-search">
                    Search:
                    <input type="text" id="charmsSearchTextbox" />
                    <ul id="searchResults">
                    </ul>
                </div>
            </div>
        </div>     
        <section id="main" class="container">                             
            <?php //echo Breadcrumbs\TBreadcrumbsControl::Exibir(); ?>      
            <?php //echo $view; ?>
            <div class="spacer"></div>
        </section>
        <footer>
            <hr />
            <p class="align-center"><strong>Copyright &copy; 2014 - <?php echo date('Y') ?> Instituto Federal de Rondônia</strong></p>           
        </footer>              
        <?php $_MyCookie->JSBundle() ?>
    </body>
</html>