<?php

use controller\user\accountType\AccountTypeControl;

__('Add', 'user');
__('Edit', 'user');
$user = $data['user'];
?>    
<header class="row">     
    <div class="col-lg-6"><h2><?php echo sprintf(__('%s user', 'user'), $data['action']); ?></h2></div>
</header>    
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <form name="FrmEdit" id="FrmEdit" role="form" onsubmit="user.submit(event);">
                    <fieldset>
                        <div class="form-group">
                            <label for="textName"><?php _e('Name', 'user') ?>*:</label>                            
                            <input type="text" required="required" name="name" id="textName" class="form-control" value="<?php echo $user->getName() ?>">                            
                        </div>  
                        <div class="form-group">
                            <label for="textMiddleName"><?php _e('Middle name', 'user') ?>:</label>                            
                            <input type="text" name="middleName" id="textMiddleName" class="form-control" value="<?php echo $user->getMiddleName() ?>">                            
                        </div>
                        <div class="form-group">
                            <label for="textLastName"><?php _e('Last name', 'user') ?>*:</label>                            
                            <input type="text" required="required" name="lastName" id="textLastName" class="form-control" value="<?php echo $user->getLastName() ?>">                            
                        </div>                
                        <div class="form-group">
                            <label for="textEmail">E-mail:</label>                            
                            <input type="email" name="email" id="textEmail" class="form-control" value="<?php echo $user->getEmail() ?>">                            
                        </div>
                        <div class="form-group">
                            <label for="selectaccountTypeId"><?php _e('Account type', 'user') ?>*:</label>                            
                            <?php AccountTypeControl::ShowSelection($user->getId(), $user->getAccountType()->getId()) ?>                                                    
                        </div>        
                        <div class="form-group">
                            <label for="textLogin"><?php _e('Username', 'user') ?>*:</label>                            
                            <input required="required" type="text" <?php if ($user->getId()) : ?>readonly="readonly"<?php endif; ?> name="login" id="textLogin" class="form-control" value="<?php echo $user->getLogin() ?>">                            
                        </div>  
                        <?php if (!$user->getId()) : ?>
                            <div class="form-group">
                                <label><?php _e('Password', 'user') ?>:</label>                                
                                <span style="font-size: 16px; font-weight: bold"><?php echo $passGen = substr(uniqid(), 7); ?></span>
                                <input type="hidden" name="newPassword" value="<?php echo $passGen; ?>">                                
                            </div>
                        <?php endif; ?>
                    </fieldset>
                    <input type="hidden" name="id" value="<?php echo $user->getId() ?>">            
                    <div class="text-right">            
                        <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> <?php _e('Save', 'user') ?></button>                        
                    </div>
                </form>                
            </div>
        </div>        
    </div>
    <?php if ($user->getId()) : ?>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php _e('Want to change the password?', 'user') ?></h3>
                </div>
                <div class="panel-body">
                    <form name="FrmEditPassword" id="FrmEditPassword" onsubmit="user.changePassword(event)">
                        <div class="form-group">
                            <label for="textActualPassword"><?php _e('Password', 'user') ?></label>                            
                            <input required="required" type="password" name="actualPassword" id="textActualPassword" class="form-control" value="" onblur="user.event_onBlurActualPassword()">                            
                        </div>
                        <div class="form-group">
                            <label for="textNewPassword"><?php _e('New password', 'user') ?>:</label>                            
                            <input required="required" pattern=".{6,32}" onchange="user.event_onChangePassword()" type="password" name="newPassword" id="textNewPassword" class="form-control" value="">                            
                        </div>
                        <div class="form-group">
                            <label for="textPasswordRepeat"><?php _e('Repeat new password', 'user') ?>:</label>                            
                            <input required="required" type="password" onchange="user.event_onChangePassword()" name="passwordRepeat" id="textPasswordRepeat" class="form-control" value="">                            
                        </div>
                        </fieldset>
                        <input type="hidden" name="id" value="<?php echo $user->getId() ?>">                
                        <div class="text-right">                           
                            <button class="btn btn-default" type="submit"><i class="fa fa-edit"></i> <?php _e('Change password', 'user') ?></button>                
                        </div>
                    </form>                    
                    <div class="text-right">
                        <br>
                        <?php if ($user->getStatus()) : ?>
                            <a href="#" onclick="user.deactivate(event)"><?php _e('Deactivate user', 'user') ?></a>
                        <?php else : ?>
                            <span class="alert-danger"><?php _e('This user is deactivated', 'user') ?></span> <a href="#" onclick="user.reactivate(event)"><?php _e('Reactivate user?', 'user') ?></a>
                        <?php endif; ?>
                        | <a href="#" onclick="user.delete(event)"><?php _e('Delete user', 'user') ?></a>                
                    </div>
                </div>
            </div>                       
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    function User() {

        var self = this;
        
        document.getElementById('textActualPassword').setCustomValidity('Incorrect password');

        this.event_onChangePassword = function() {
            if ($('#textNewPassword').val() !== $('#textPasswordRepeat').val()) {
                document.getElementById('textPasswordRepeat').setCustomValidity('Passwords do not match');
            } else {
                document.getElementById('textPasswordRepeat').setCustomValidity('');
            }
        };

        this.event_onBlurActualPassword = function() {
            var msg = MyCookieJS.execute('user/checkActualPassword', $('#FrmEditPassword').serialize(), false);
            if (msg === 'false') {
                document.getElementById('textActualPassword').setCustomValidity('Incorrect password');
            }
            else {
                document.getElementById('textActualPassword').setCustomValidity('');
            }
        };

        this.deactivate = function(e) {
            e.preventDefault();
            var msg = MyCookieJS.execute('user/deactivate', $('#FrmEdit').serialize(), false);
            if (msg !== '') {
                alert(msg);
            }
            else {
                MyCookieJS.alert(t('user:user_deactivated'));
                MyCookieJS.alert('Usuário desativado com sucesso!', function() {
                    MyCookieJS.goto('administrator/user');
                });
            }
        };

        this.reactivate = function(e) {
            e.preventDefault();
            var msg = MyCookieJS.execute('user/reactivate', $('#FrmEdit').serialize(), false);
            if (msg !== '') {
                alert(msg);
            }
            else {
                MyCookieJS.alert('Usuário reativado com sucesso!', function() {
                    MyCookieJS.goto('administrator/user');
                });
            }
        };

        this.changePassword = function(e) {
            e.preventDefault();
            var msg = MyCookieJS.execute('user/changePassword', $('#FrmEditPassword').serialize(), false);
            if (msg !== '') {
                alert(msg);
            }
            else {
                MyCookieJS.alert('Senha alterada com sucesso!', function() {
                    MyCookieJS.goto('administrator/user');
                });
            }
        };

        this.delete = function(e) {
            e.preventDefault();
            MyCookieJS.confirm('Deseja realmente deletar este usuário?', function() {
                var msg = MyCookieJS.execute('user/delete', $('#FrmEdit').serialize(), false);
                if (msg !== '') {
                    alert(msg);
                }
                else {
                    MyCookieJS.alert('Usuário deletado com sucesso!', function() {
                        MyCookieJS.goto('administrator/user');
                    });
                }
            });
        };

        this.submit = function(e) {
            e.preventDefault();
            var msg = MyCookieJS.execute('user/save', $('#FrmEdit').serialize(), false);
            if (msg !== '') {
                alert(msg);
            }
            else {
                MyCookieJS.alert('Usuário salvo com sucesso!', function() {
                    MyCookieJS.goto('administrator/user');
                });
            }
        };
    }

    var user = new User();
</script>