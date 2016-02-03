<div class="modal" id="mdReset">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" data-i18n="user:reset.title">Reseting password</h4>
            </div>            
            <form id="FrmReset" onsubmit="user.reset(event, '<?php echo $data ?>')">
                <div class="modal-body">                     
                    <fieldset>                        
                        <div class="form-group">
                            <label for="textNewPassword"><span data-i18n="user:label.new_password">New password</span>:</label>                            
                            <input required="required" pattern=".{6,32}" onchange="user.event_onChangePassword()" type="password" name="newPassword" id="textNewPassword" class="form-control" value="">                            
                        </div>
                        <div class="form-group">
                            <label for="textPasswordRepeat"><span data-i18n="user:label.repeat_new_pwd">Repeat new password</span>:</label>                            
                            <input required="required" type="password" onchange="user.event_onChangePassword()" name="passwordRepeat" id="textPasswordRepeat" class="form-control" value="">                            
                        </div>
                    </fieldset>                
                </div>                
                <div class="modal-footer">                
                    <input type="hidden" name="key" value="<?= filter_input(INPUT_GET, 'key') ?>">
                    <button type="submit" class="btn btn-success"><i class="fa fa-sign-in"></i> <span data-i18n="user:reset.button">Change password</span></button>
                </div>            
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    require(['jquery'], function ($) {
        $(function () {
            $('#mdReset').modal({
                show: true,
                keyboard: false,
                backdrop: false
            });
            $('#textNewPassword').focus();
        });
    });
</script>