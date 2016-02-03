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
        <title data-i18n="index:site.title">MyCookie - Starter page</title>                        
        <?php $_MyCookie->CSSBundle() ?>        
        <?php $_MyCookie->RequireJS() ?>      
    </head>
    <body id="index-body">        
        <header class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">MyCookie</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#home" role="tab" data-toggle="tab"><i class="fa fa-home"></i> <span data-i18n="index:nav.home">Home</span></a></li>
                        <li><a href="#programacao" role="tab" data-toggle="tab" data-i18n="index:nav.about">About</a></li>
                        <li><a href="#local" role="tab" data-toggle="tab" data-i18n="index:nav.features">Features</a></li>
                        <li><a href="#local" role="tab" data-toggle="tab"><i class="fa fa-file-text"></i> <span data-i18n="index:nav.docs">Documentation</span></a></li>
                        <li><a href="administrator/"><i class="fa fa-file-text"></i> <span data-i18n="index:nav.admin">Administration</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </header>
        <section class="container">                                         
            <?php echo $view ?>
            <div class="spacer"></div>
        </section>         
        <footer>
            <hr />
            <p class="text-center"><strong>Copyright &copy; 2014 - 2015 IFRO</strong><br>Instituto Federal de Educação, Ciência e Tecnologia de Rondônia<br><i>Campus Ariquemes</i></p>           
        </footer>              
        <?php $_MyCookie->JSBundle() ?>         
    </body>
</html>