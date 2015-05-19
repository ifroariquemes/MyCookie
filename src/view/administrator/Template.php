<?php

use controller\administrator\menu\MenuControl;

/* @var $_MyCookie \lib\MyCookie */
global $_MyCookie;
/* @var $_User model\user\User */
global $_User;
global $_MyCookieGoBack;
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
        <title><?php _e('administrative panel', 'user') ?></title>                        
        <?php $_MyCookie->CSSBundle() ?>
        <?php $_MyCookie->RequireJS() ?>      
    </head>
    <body id="admin-body">        
        <header id="admin-header" class="container">
            <div class="row">               
                <?php if ($_MyCookie->getModule() == 'administrator') : ?>
                    <div class="col-lg-6 col-sm-6 col-xs-5">
                        <h1><?php _e('administrative panel', 'administrator') ?></h1>
                    </div>
                <?php else : ?>                
                    <div class="col-lg-1 col-sm-1 col-xs-2">
                        <a id="goBackButton" href="<?php echo MenuControl::getBackLink() ?>">
                            <i class="fa fa-5x fa-arrow-circle-left"></i>
                        </a>
                    </div>
                    <div class="col-lg-5 col-sm-5 col-xs-5">
                        <div id="header-container">                            
                            <h4><?php _e('administrative panel', 'administrator') ?></h4>
                            <div class="dropdown">
                                <a class="header-dropdown dropdown-toggle accent-color" data-toggle="dropdown" href="#" >
                                    <?php _e(MenuControl::ListCurrentModuleName(), 'administrator') ?>
                                    <b class="caret" href="#"></b>
                                </a>
                                <?php MenuControl::ListModuleNames() ?>                    
                            </div>
                        </div>   
                    </div>
                <?php endif; ?>                                 
                <div class="col-lg-6 col-sm-6 col-xs-5">
                    <a class="user-info dropdown-toggle" href="#" data-toggle="dropdown">    
                        <div style="float: left">
                            <h3><?php echo $_User->getName() ?></h3>
                            <h4><?php echo $_User->getLastName() ?></h4>     
                            <div id="user_completename" class="hidden"><?php echo $_User->getCompleteName() ?></div>
                        </div>
                        <i class="fa fa-4x fa-user"></i>
                    </a>                    
                    <ul id="profile-dropdown" class="dropdown-menu" role="menu">
                        <li><a href="<?php echo $_MyCookie->mountLink('administrator', 'user', 'edit', $_User->getId()) ?>"><?php _e('Edit profile', 'administrator') ?></a></li>                       
                        <li class="divider"></li>
                        <li><a href="<?php echo $_MyCookie->mountLink('user', 'logout') ?>"><i class="fa fa-sign-out"></i> <?php _e('Sign out', 'administrator') ?></a></li>
                    </ul>
                </div>                                                        
            </div>        
        </header>                   
        <section id="main" class="container">                             
            <?php echo $view ?>
            <div class="spacer"></div>
        </section> 
        <?php if ($_MyCookie->getModule() == 'administrator') : ?>
            <footer id="admin-footer">
                <hr />
                <p class="align-center"><strong>Copyright &copy; 2014 IFRO</strong><br>Instituto Federal de Educação, Ciência e Tecnologia de Rondônia<br><i>Campus Ariquemes</i></p>           
            </footer>              
        <?php endif; ?>
        <?php $_MyCookie->JSBundle() ?>                  
        <?php if (!empty($_MyCookieGoBack)) : ?>
            <script type="text/javascript">
                require(['jquery'], function($) {
                    $(function() {
                        $('#goBackButton').attr('href', '<?php echo $_MyCookieGoBack ?>');
                    });
                });
            </script>
        <?php endif; ?>        
    </body>
</html>