function User() {

    var self = this;

    this.event_onChangePassword = function () {
        if ($('#textNewPassword').val() !== $('#textPasswordRepeat').val()) {
            document.getElementById('textPasswordRepeat').setCustomValidity('Passwords do not match');
        } else {
            document.getElementById('textPasswordRepeat').setCustomValidity('');
        }
    };

    this.event_onBlurActualPassword = function () {
        var msg = MyCookieJS.execute('user/checkActualPassword', $('#FrmEditPassword').serialize(), false);
        if (msg === 'false') {
            document.getElementById('textActualPassword').setCustomValidity('Incorrect password');
        }
        else {
            document.getElementById('textActualPassword').setCustomValidity('');
        }
    };

    this.deactivate = function (e) {
        e.preventDefault();
        MyCookieJS.showWaitMessage();
        MyCookieJS.execute('user/deactivate', $('#FrmEdit').serialize(), true, function (msg) {
            if (msg !== '') {
                alert(msg);
            }
            else {
                MyCookieJS.alert($.i18n.t('user:message.user_deactivated'), function () {
                    MyCookieJS.goto('administrator/user');
                });
            }
        });
    };

    this.reactivate = function (e) {
        e.preventDefault();
        var msg = MyCookieJS.execute('user/reactivate', $('#FrmEdit').serialize(), false);
        if (msg !== '') {
            alert(msg);
        }
        else {
            MyCookieJS.alert('Usuário reativado com sucesso!', function () {
                MyCookieJS.goto('administrator/user');
            });
        }
    };

    this.changePassword = function (e) {
        e.preventDefault();
        var msg = MyCookieJS.execute('user/changePassword', $('#FrmEditPassword').serialize(), false);
        if (msg !== '') {
            alert(msg);
        }
        else {
            MyCookieJS.alert('Senha alterada com sucesso!', function () {
                MyCookieJS.goto('administrator/user');
            });
        }
    };

    this.delete = function (e) {
        e.preventDefault();
        MyCookieJS.confirm('Deseja realmente deletar este usuário?', function () {
            var msg = MyCookieJS.execute('user/delete', $('#FrmEdit').serialize(), false);
            if (msg !== '') {
                alert(msg);
            }
            else {
                MyCookieJS.alert('Usuário deletado com sucesso!', function () {
                    MyCookieJS.goto('administrator/user');
                });
            }
        });
    };

    this.submit = function (e) {
        e.preventDefault();
        $('#FrmEdit').find(':disabled').attr('disabled', false);
        var msg = MyCookieJS.execute('user/save', $('#FrmEdit').serialize(), false);
        if (msg !== '') {
            alert(msg);
        }
        else {
            MyCookieJS.alert('Usuário salvo com sucesso!', function () {
                MyCookieJS.goto('administrator/user');
            });
        }
    };

    this.checkPublicValidity = function () {
        if (!$('#FrmEdit')[0].checkValidity || $('#FrmEdit')[0].checkValidity()) {
            $('#FrmEdit button[type="submit"]').attr('disabled', false);
        } else {
            $('#FrmEdit button[type="submit"]').attr('disabled', true);
        }
    };

    this.checkResendValidity = function () {
        if (!$('#FrmResend')[0].checkValidity || $('#FrmResend')[0].checkValidity()) {
            $('#FrmResend button[type="submit"]').attr('disabled', false);
        } else {
            $('#FrmResend button[type="submit"]').attr('disabled', true);
        }
    };

    this.checkResendValidity = function () {
        if (!$('#FrmForgot')[0].checkValidity || $('#FrmForgot')[0].checkValidity()) {
            $('#FrmForgot button[type="submit"]').attr('disabled', false);
        } else {
            $('#FrmForgot button[type="submit"]').attr('disabled', true);
        }
    };

    this.verifyEmailPublic = function (e) {
        e.preventDefault();
        MyCookieJS.showWaitMessage();
        MyCookieJS.execute('user/verifyEmail', $('#FrmEdit').serialize(), true, function (msg) {
            if (msg != '') {
                MyCookieJS.alert(msg, 'mdRegister');
                $('#textEmail').focus();
            }
            else if (!$('#FrmEdit')[0].checkValidity || $('#FrmEdit')[0].checkValidity()) {
                self.submitPublic();
            }
            /*MyCookieJS.gotoPopup('mdRegister', function (e) {
             $('#FrmEdit input[type=submit]').click();
             });*/
        });
    };

    this.submitPublic = function () {
        //e.preventDefault();
        MyCookieJS.showWaitMessage($.i18n.t('user:message.saving'));
        MyCookieJS.execute('user/savePublic', $('#FrmEdit').serialize(), true, function (msg) {
            MyCookieJS.alert(msg, function () {
                MyCookieJS.closeAllPopups();
                $('#FrmEdit')[0].reset();
            });
        });
    };

    this.resend = function (e) {
        e.preventDefault();
        MyCookieJS.showWaitMessage();
        MyCookieJS.execute('user/resend', $('#FrmResend').serialize(), true, function (msg) {
            MyCookieJS.alert(msg, function () {
                MyCookieJS.closeWaitMessage();
                MyCookieJS.closeAllPopups();
                $('#FrmResend')[0].reset();
            });
        });
    };

    this.forgot = function (e) {
        e.preventDefault();
        MyCookieJS.showWaitMessage();
        MyCookieJS.execute('user/forgot', $('#FrmForgot').serialize(), true, function (msg) {
            MyCookieJS.alert(msg, function () {
                MyCookieJS.closeWaitMessage();
                MyCookieJS.closeAllPopups();
                $('#FrmForgot')[0].reset();
            });
        });
    };

    this.reset = function (e, href) {
        e.preventDefault();
        MyCookieJS.showWaitMessage();
        MyCookieJS.execute('user/reset', $('#FrmReset').serialize(), true, function (msg) {
            MyCookieJS.alert(msg, function () {
                location.href = href;
            });
        });
    };

    this.search = function (e) {
        e.preventDefault();
        MyCookieJS.goto('administrator/user/search/?q=' + $('#textName').val());
    }
}

user = new User();