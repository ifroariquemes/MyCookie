<?php

use lib\MyCookie;

/* @var $_MyCookie MyCookie */
global $_MyCookie;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Painel Administrativo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $_MyCookie->CSSBundle() ?>        
    </head>
    <body>        
        <div class="container">                        
            <div class="col-lg-offset-4 col-lg-4">
                <h2>painel administrativo</h2>
                <form id="FrmLogin" method="post" class="jumbotron" action="<?php echo "{$_MyCookie->getSite()}user/login"; ?>">                    
                    <p><?php echo $_SESSION[MyCookie::MessageSession] ?></p>
                    <div class="input-group input-group-lg">                        
                        <label for="login">Nome de usuário</label>
                        <input type="text" class="form-control" placeholder="Login" name="login" id="login" required="required">                        
                    </div>
                    <div class="input-group input-group-lg">                        
                        <label for="login">Senha</label>
                        <input type="text" class="form-control" placeholder="Senha" name="password" id="password" required="required">
                    </div><br>
                    <p class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary">Entrar</button>
                    </p>
                </form>                                
            </div>            
        </div>
        <?php $_MyCookie->JSBundle() ?>
        <script type="text/javascript">
            require(['jquery'], function($) {
                $('#login').focus();
            });
        </script>
    </body>
</html>