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
        <style type="text/css">    
            #admin-header {
                margin-top: 40px;
            }
            #admin-header .user-info {                
                position: relative;
                text-align: right;  
                float:right;
                padding: 0px 10px;
            }
            #admin-header .user-info:hover {                
                text-decoration: none;
                background-color: #eee;
            }
            #admin-header .user-info h3 {
                left: -20px;
            }
            #admin-header .user-info i {
                position: relative;
                top: 20px;
                margin-left: 10px;
            }
            #admin-header .dropdown-menu {
                left: auto !important;
                right: 15px;
            }
            #admin-tile-section .thumbnail {
                margin-right: 10px;
            }
            #admin-tile-section .thumbnail:hover {
                text-decoration: none;
            }
        </style>        
    </head>
    <body>        
        <header id="admin-header" class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1><?php _e('administrative panel', 'user') ?></h1>
                </div>                  
                <div class="col-lg-offset-3 col-lg-3 btn-group">                                        
                    <a class="user-info dropdown-toggle" href="#" data-toggle="dropdown">    
                        <div style="float: left">
                            <h3><?php echo $_MyCookieUser->getName() ?></h3>
                            <h4><?php echo $_MyCookieUser->getLastName() ?></h4>                                                                                
                        </div>
                        <i class="fa fa-4x fa-user"></i>
                    </a>                    
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#"><?php _e('Edit profile', 'user') ?></a></li>                       
                        <li class="divider"></li>
                        <li><a href="user/logout"><?php _e('Sign out', 'user') ?></a></li>
                    </ul>
                </div>                                                        
            </div>        
        </header>    

        <section id="tiles" class="container">
            <?php echo $view ?>
        </section>

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