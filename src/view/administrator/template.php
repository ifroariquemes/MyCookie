<?php

use controller\administrator\menu\MenuControl;

global $_MyCookie;
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
        <title data-i18n="admin:page.title">administrative panel</title>                        
        <?php $_MyCookie->CSSBundle() ?>
        <?php $_MyCookie->RequireJS() ?>      
    </head>
    <body id="admin-body">        
        <header id="admin-header" class="container">
            <div class="row">               
                <?php if ($_MyCookie->getModule() == 'administrator') : ?>
                    <div class="col-lg-6 col-sm-6 col-xs-5">
                        <h1 data-i18n="admin:header.title">administrative panel</h1>
                    </div>
                <?php else : ?>                
                    <div class="col-lg-1 col-sm-1 col-xs-2">
                        <a id="goBackButton" href="<?= MenuControl::getBackLink() ?>">
                            <i class="fa fa-5x fa-arrow-circle-left"></i>
                        </a>
                    </div>
                    <div class="col-lg-5 col-sm-5 col-xs-5">
                        <div id="header-container">                            
                            <h4 data-i18n="admin:header.title">administrative panel</h4>
                            <div class="dropdown">
                                <a class="header-dropdown dropdown-toggle accent-color" data-toggle="dropdown" href="#" >
                                    <?= MenuControl::listCurrentModuleName() ?>
                                    <b class="caret" href="#"></b>
                                </a>
                                <?php MenuControl::listModuleNames() ?>                    
                            </div>
                        </div>   
                    </div>
                <?php endif; ?>                                 
                <div class="col-lg-6 col-sm-6 col-xs-5">
                    <a class="user-info dropdown-toggle" href="#" data-toggle="dropdown">    
                        <div style="float: left">
                            <h3><?= $_User->getFirstName() ?></h3>
                            <h4><?= $_User->getLastName() ?></h4>     
                            <div id="user_completename" class="hidden"><?= $_User->getName() ?></div>
                        </div>
                        <i class="fa fa-4x fa-user"></i>
                    </a>                    
                    <ul id="profile-dropdown" class="dropdown-menu" role="menu">
                        <li><a href="<?= $_MyCookie->mountLink('administrator', 'user', 'edit', $_User->getId()) ?>" data-i18n="admin:button.edit_profile">Edit profile</a></li>                       
                        <li class="divider"></li>
                        <li><a href="<?= $_MyCookie->mountLink('user', 'logout') ?>"><i class="fa fa-sign-out"></i> <span data-i18n="admin:button.signout">Sign out</span></a></li>
                    </ul>
                </div>                                                        
            </div>        
        </header>                   
        <section id="main" class="container">                             
            <?= $view ?>
            <div class="spacer"></div>
        </section> 
        <?php if ($_MyCookie->getModule() == 'administrator') : ?>
            <footer id="admin-footer">
                <hr />
                <p class="align-center">
                    <strong>Copyright &copy; 2014 - 2015 IFRO</strong><br>
                    <span data-i18n="admin:footer.institute">Federal Institute of Rondônia</span><br>
                    <i>Campus Ariquemes</i>
                </p>           
            </footer>              
        <?php endif; ?>
        <?php $_MyCookie->JSBundle() ?>                  
        <?php if (!empty($_MyCookieGoBack)) : ?>
            <script type="text/javascript">
                require(['jquery'], function ($) {
                    $(function () {
                        $('#goBackButton').attr('href', '<?php echo $_MyCookieGoBack ?>');
                    });
                });
            </script>
        <?php endif; ?>        
    </body>
</html>