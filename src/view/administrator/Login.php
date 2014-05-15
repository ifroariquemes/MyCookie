<?php

use lib\MyCookie;

/* @var $_MyCookie MyCookie */
global $_MyCookie;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php _e('administrative panel', 'administrator') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $_MyCookie->CSSBundle() ?>        
        <?php $_MyCookie->RequireJS() ?>
    </head>
    <body>        
        <div class="container">                        
            <div class="col-lg-offset-4 col-lg-4">
                <h2 data-i18n="administrator:header.title"></h2>
                <form id="FrmLogin" method="post" class="jumbotron" action="<?php echo "{$_MyCookie->getSite()}user/login"; ?>">                    
                    <p><?php echo $_SESSION[MyCookie::MessageSession] ?></p>
                    <div class="input-group input-group-lg">                        
                        <label for="login"><?php _e('Username', 'administrator') ?></label>
                        <input type="text" class="form-control" placeholder="<?php _e('Login', 'administrator') ?>" name="login" id="login" required="required">                        
                    </div>
                    <div class="input-group input-group-lg">                        
                        <label for="login"><?php _e('Password', 'administrator') ?></label>
                        <input type="password" class="form-control" placeholder="<?php _e('Password', 'administrator') ?>" name="password" id="password" required="required">
                    </div><br>
                    <p class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-sign-in"></i> <?php _e('Sign in', 'administrator') ?></button>
                    </p>
                </form>                                
            </div>            
        </div>
        <?php $_MyCookie->JSBundle() ?>
        <script type="text/javascript">
            require(['jquery'], function($) {
                $('#login').focus();
                $('.container').i18n();
            });
        </script>
    </body>
</html>