<?php

use lib\MyCookie;

global $_MyCookie;
global $_BaseURL;
global $_Config;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title data-i18n="admin:page.title">administrative panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $_MyCookie->CSSBundle() ?>        
        <?php $_MyCookie->RequireJS() ?>
    </head>
    <body>        
        <div class="container">                        
            <div class="col-lg-offset-4 col-lg-4">
                <h2 data-i18n="admin:header.title">administrative panel</h2>
                <form id="FrmLogin" method="post" class="jumbotron" action="<?php echo "{$_BaseURL}user/login"; ?>">                    
                    <p class="text-danger"><?php echo $_SESSION[MyCookie::MESSAGE_SESSION] ?></p>
                    <div class="input-group input-group-lg">                        
                        <label for="login" data-i18n="admin:label.email">E-mail</label>
                        <input type="text" class="form-control" name="email" id="email" required="required" data-i18n="[placeholder]admin:label.email_pla">                        
                    </div>
                    <div class="input-group input-group-lg">                        
                        <label for="login" data-i18n="admin:label.password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required="required" data-i18n="[placeholder]admin:label.password_pla">                        
                    </div>
                    <!--<div class="input-group input-group-lg">                        
                        <label for="login">
                            <input type="checkbox" name="rememberme" id="checkRemember"> 
                            <span data-i18n="admin:label.rememberme">Remember me?</span>
                        </label> 
                    </div>--><br>
                    <p class="text-center">
                        <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-sign-in"></i> <span data-i18n="admin:button.signin">Sign in</span></button><br>
                    </p>                     
                    <div class="text-center"><a href="#" onclick="$('#mdForgot').modal('show')" data-i18n="admin:label.forgot">Forgot your password?</a></div>
                </form>       
                <?php if ($_Config->allowPublicSignup) : ?>
                    <h4 class="text-center" data-i18n="admin:label.noaccount">You do not have an account?</h4>                    
                    <p class="text-center">
                        <button type="button" class="btn btn-lg btn-success" onclick="$('#mdRegister').modal('show');
                                    $('#textName').focus()"><i class="fa fa-user"></i> <span data-i18n="admin:button.register">Register new account</span></button><br>                    
                    </p>
                    <p class="text-center"><a href="#" onclick="$('#mdResend').modal('show');
                                $('#textEmailRe').focus()" data-i18n="admin:button.resend">Resend confirmation link.</a></p>
                    <?php endif; ?>
            </div>            
        </div>
        <div class="modal" id="mdForgot">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" data-i18n="admin:label.forgot">Forgot your password?</h4>
                    </div>
                    <form name="FrmForgot" id="FrmForgot" role="form" onsubmit="user.forgot(event);" autocomplete="off">
                        <div class="modal-body">                                        
                            <fieldset>                                
                                <div class="form-group">
                                    <label for="textEmailRe"><span data-i18n="user:label.email">E-mail</span>:</label>                            
                                    <input type="email" name="email" id="textEmailRe" required="required" class="form-control" value="" onkeypress="user.checkResendValidity()" onchange="user.checkForgotValidity()">                            
                                </div>                                                                                                                                  
                            </fieldset>                                                        
                        </div>                
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" onclick="$('#mdForgot').modal('hide')"><i class="fa fa-times"></i> <span data-i18n="mycookie:button.cancel">Cancel</span></button>
                            <button disabled="disabled" type="submit" class="btn btn-success"><i class="fa fa-mail-forward"></i> <span data-i18n="admin:forgot.button">Reset my password</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if ($_Config->allowPublicSignup) : ?>
            <div class="modal" id="mdRegister">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" data-i18n="admin:register.title">Register new account</h4>
                        </div>
                        <form name="FrmEdit" id="FrmEdit" role="form" autocomplete="off">
                            <div class="modal-body">                                        
                                <fieldset>
                                    <div class="form-group">
                                        <label for="textName"><span data-i18n="user:label.name">Name</span>:</label>                            
                                        <input type="text" required="required" name="name" id="textName" class="form-control" onkeyup="user.checkPublicValidity()">                            
                                    </div>                                                                      
                                    <div class="form-group">
                                        <label for="textEmail"><span data-i18n="user:label.email">E-mail</span>:</label>                            
                                        <input type="email" name="email" id="textEmail" required="required" class="form-control" onkeyup="user.checkPublicValidity()">                            
                                    </div>                                                                                                  
                                    <div class="form-group">
                                        <label for="textPassword"><span data-i18n="user:label.password">Password</span>:</label> (<span data-i18n="user:message.pwd_minimum">minimum of 6 characters</span>)                           
                                        <input required="required" pattern=".{6,32}" onkeyup="user.checkPublicValidity()" onchange="$('#textPasswordRepeat').attr('pattern', this.value)" type="password" name="newPassword" id="textPassword" class="form-control" value="">                            
                                    </div>
                                    <div class="form-group">
                                        <label for="textPasswordRepeat"><span data-i18n="user:label.repeat_pwd">Repeat password</span>:</label>                            
                                        <input required="required" pattern="" type="password" name="passwordRepeat" id="textPasswordRepeat" class="form-control" onkeyup="user.checkPublicValidity()">                            
                                    </div>
                                </fieldset>                                
                                <input type="hidden" name="id" value="">                                          
                            </div>                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="$('#FrmEdit')[0].reset();
                                            $('#mdRegister').modal('hide')"><i class="fa fa-times"></i> <span data-i18n="mycookie:button.cancel">Cancel</span></button>                                
                                <button type="submit" class="btn btn-success" onclick="user.verifyEmailPublic(event)" disabled="disabled"><i class="fa fa-sign-in"></i> <span data-i18n="admin:register.button">Register</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal" id="mdResend">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" data-i18n="admin:resend.title">Resend confirmation link</h4>
                        </div>
                        <form name="FrmResend" id="FrmResend" role="form" onsubmit="user.resend(event);" autocomplete="off">
                            <div class="modal-body">                                        
                                <fieldset>                                
                                    <div class="form-group">
                                        <label for="textEmailRe"><span data-i18n="user:label.email">E-mail</span>:</label>                            
                                        <input type="email" name="email" id="textEmailRe" required="required" class="form-control" value="" onkeypress="user.checkResendValidity()" onchange="user.checkResendValidity()">                            
                                    </div>                                                                                                                                  
                                </fieldset>
                                <p data-i18n="admin:resend.spam">If you yet don't received any e-mail with confirmation link, please check your spam folder.</p>                                                                                                                    
                            </div>                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="$('#mdResend').modal('hide')"><i class="fa fa-times"></i> <span data-i18n="mycookie:button.cancel">Cancel</span></button>
                                <button disabled="disabled" type="submit" class="btn btn-success"><i class="fa fa-mail-forward"></i> <span data-i18n="admin:resend.button">Resend</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal" id="mdResend">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" data-i18n="admin:resend.title">Resend confirmation link</h4>
                        </div>
                        <form name="FrmResend" id="FrmResend" role="form" onsubmit="user.resend(event);">
                            <div class="modal-body">                                        
                                <fieldset>                                
                                    <div class="form-group">
                                        <label for="textEmailRe"><span data-i18n="user:label.email">E-mail</span>:</label>                            
                                        <input type="email" name="email" id="textEmailRe" required="required" class="form-control" value="" onkeypress="user.checkResendValidity()" onchange="user.checkResendValidity()">                            
                                    </div>                                                                                                                                  
                                </fieldset>
                                <p data-i18n="admin:resend.spam">If you yet don't received any e-mail with confirmation link, please check your spam folder.</p>                                                                                    
                                <input type="submit" style="position: absolute; visibility: hidden">
                            </div>                
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="$('#mdResend').modal('hide')"><i class="fa fa-times"></i> <span data-i18n="mycookie:button.cancel">Cancel</span></button>
                                <button disabled="disabled" type="submit" class="btn btn-success"><i class="fa fa-mail-forward"></i> <span data-i18n="admin:resend.button">Resend</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php $_MyCookie->JSBundle() ?>
        <script type="text/javascript">
            require(['jquery', 'i18next'], function ($) {
                $('#email').focus();
            });
        </script>
    </body>
</html>