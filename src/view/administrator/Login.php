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
                    <p><?php echo $_SESSION[MyCookie::MESSAGE_SESSION] ?></p>
                    <div class="input-group input-group-lg">                        
                        <label for="login"><?php _e('Username', 'administrator') ?></label>
                        <input type="text" class="form-control" placeholder="<?php _e('Login', 'administrator') ?>" name="login" id="login" required="required">                        
                    </div>
                    <div class="input-group input-group-lg">                        
                        <label for="login"><?php _e('Password', 'administrator') ?></label>
                        <input type="password" class="form-control" placeholder="<?php _e('Password', 'administrator') ?>" name="password" id="password" required="required"><br>
                        <a href="#"><?php _e('Forgot your password', 'administrator') ?>?</a>
                    </div><br>
                    <p class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-sign-in"></i> <?php _e('Sign in', 'administrator') ?></button>
                    </p>                    
                </form>       
                <?php if ($_MyCookie->getMyCookieConfiguration()->allowPublicSignup) : ?>
                    <h4 class="text-center"><?php _e('You do not have an account?', 'administrator') ?></h4>                    
                    <p class="text-center">
                        <button type="button" class="btn btn-lg btn-success" onclick="$('#mdRegister').modal('show')"><i class="fa fa-user"></i> <?php _e('Register new account', 'administrator') ?></button><br>                    
                    </p>
                    <p class="text-center"><a href="#" onclick="$('#mdResend').modal('show');
                                $('#textEmailRe').focus()">Resend confirmation link.</a></p>
                    <?php endif; ?>
            </div>            
        </div>
        <?php if ($_MyCookie->getMyCookieConfiguration()->allowPublicSignup) : ?>
            <div class="modal" id="mdRegister">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php _e('Register new account', 'administrator') ?></h4>
                        </div>
                        <form name="FrmEdit" id="FrmEdit" role="form" onsubmit="user.userSubmit(event);">
                            <div class="modal-body">                                        
                                <fieldset>
                                    <div class="form-group">
                                        <label for="textName"><?php _e('Name', 'user') ?>:</label>                            
                                        <input type="text" required="required" name="name" id="textName" class="form-control" value="">                            
                                    </div>                                  
                                    <div class="form-group">
                                        <label for="textLastName"><?php _e('Last name', 'user') ?>:</label>                            
                                        <input type="text" required="required" name="lastName" id="textLastName" class="form-control" value="">                            
                                    </div>                
                                    <div class="form-group">
                                        <label for="textEmail">E-mail:</label>                            
                                        <input type="email" name="email" id="textEmail" required="required" class="form-control" value="">                            
                                    </div>                                                                                                  
                                    <div class="form-group">
                                        <label for="textPassword"><?php _e('Password', 'user') ?>:</label> (<?php _e('minimum of 6 characters', 'user') ?>)                           
                                        <input required="required" pattern=".{6,32}" onchange="$('#textPasswordRepeat').attr('pattern', this.value)" type="password" name="newPassword" id="textPassword" class="form-control" value="">                            
                                    </div>
                                    <div class="form-group">
                                        <label for="textPasswordRepeat"><?php _e('Repeat password', 'user') ?>:</label>                            
                                        <input required="required" pattern="" type="password" name="passwordRepeat" id="textPasswordRepeat" class="form-control" value="">                            
                                    </div>
                                </fieldset>
                                <input type="hidden" name="accountTypeId" value="2">
                                <input type="hidden" name="id" value="">          
                                <input type="submit" style="position: absolute; visibility: hidden">
                            </div>                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="$('#mdRegister').modal('hide')"><i class="fa fa-times"></i> <?php _e('Cancel', 'administrator') ?></button>
                                <button type="button" class="btn btn-success" onclick="user.verifyUsername()"><i class="fa fa-sign-in"></i> <?php _e('Register', 'administrator') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal" id="mdResend">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php _e('Resend confirmation link', 'administrator') ?></h4>
                        </div>
                        <form name="FrmResend" id="FrmResend" role="form" onsubmit="user.resend(event);">
                            <div class="modal-body">                                        
                                <fieldset>                                
                                    <div class="form-group">
                                        <label for="textEmailRe">E-mail:</label>                            
                                        <input type="email" name="email" id="textEmailRe" required="required" class="form-control" value="">                            
                                    </div>                                                                                                                                  
                                </fieldset>
                                <p>If you yet don't received any e-mail with confirmation link, please check your spam folder.</p>                                                                                    
                                <input type="submit" style="position: absolute; visibility: hidden">
                            </div>                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="$('#mdResend').modal('hide')"><i class="fa fa-times"></i> <?php _e('Cancel', 'administrator') ?></button>
                                <button type="button" class="btn btn-success" onclick="user.resend(event)"><i class="fa fa-mail-forward"></i> <?php _e('Resend', 'administrator') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php $_MyCookie->JSBundle() ?>
        <script type="text/javascript">
            require(['jquery'], function($) {
                $('#login').focus();
                $('.container').i18n();
            });
        </script>
    </body>
</html>