/**
 * Principal classe JavaScript do CMS MyCookie
 * @class TMyCookieJS
 */
function TMyCookieJS() {

    var self = this;
    var openingPopup = false;
    var confirmResult = false;

    /**          
     * @property {TMyCookieJSErrors} MyCookieJSErrors Handles errors          
     */
    var MyCookieJSErrors = new TMyCookieJSErrors();

    /**
     * Mounts a URL based on requested module/submodule/action
     * @param {string} moduleAction References modules, submodules and action in MyCookie
     * @param {string} withTemplate If will load with template or not
     * @return {string} Mounted URL
     * @example mountURL('news/list');
     */
    this.mountURL = function(moduleAction, withTemplate) {
        return String.format('{0}{1}/{2}', self.getSite(), moduleAction, (withTemplate) ? '' : '?async');
    };

    /**
     * @return {string} Main action being executed at MyCookie
     */
    this.getAction = function() {
        return MYCOOKIEJS_ACTION;
    };

    /**
     * @return {string} Main module being executed at MyCookie
     */
    this.getModule = function() {
        return MYCOOKIEJS_MODULE;
    }

    /**
     * @return {string} Main auxiliar module being executed at MyCookie
     */
    this.getAuxiliarModule = function() {
        return MYCOOKIEJS_AUXILIARMODULE;
    }

    /**
     * @return {string} Namespace of main module being executed at MyCookie
     */
    this.getNamespace = function() {
        return MYCOOKIEJS_NAMESPACE;
    }

    /**
     * @return {string} Base URL from project
     */
    this.getSite = function() {
        return MYCOOKIEJS_SITE;
    }

    /**
     * Shows a popup with some content
     * @param {string} popupId The popup id
     * @param {string} content The content to show
     * @param {function} onShow Something to do on show
     * @returns {Boolean}
     */
    this.showStaticPopup = function(popupId, content, onShow, onHide) {
        var modal, modaldialog, modalcontent, modalprevious, modalbackdrop, previousPopupId, idPopup;
        /*try {*/
        openingPopup = true;
        previousPopupId = $('.modal.in').attr('id');
        if (typeof (previousPopupId) === 'string' && previousPopupId !== '') {
            self.closePopup(previousPopupId, openingPopup);
        }
        else if ($('.modal-backdrop').length === 0) {
            modalbackdrop = document.createElement('div');
            $(modalbackdrop)
                    .addClass('modal-backdrop')
                    .addClass('fade')
                    .addClass('in');
            $('body').append(modalbackdrop);
        }

        $(String.format("#{0}", popupId)).remove();

        modalcontent = document.createElement('div');
        $(modalcontent)
                .addClass('modal-content')
                .append(content);

        modaldialog = document.createElement('div');
        $(modaldialog)
                .addClass('modal-dialog')
                .append(modalcontent);

        modalprevious = document.createElement('div');
        $(modalprevious)
                .addClass('hidden')
                .addClass('idPopupAnterior')
                .append(previousPopupId);

        modal = document.createElement('div');
        $(modal)
                .addClass('modal')
                .addClass('fade')
                .attr('role', 'modal')
                .attr('tab-index', '-1')
                .attr('id', popupId)
                .append(modaldialog)
                .append(modalprevious)
                .on('shown.bs.modal', function() {
                    if (typeof (onShow) === 'function') {
                        onShow();
                    }
                })
                .on('hidden.bs.modal', function() {
                    if (typeof (onHide) === 'function') {
                        onHide();
                    }
                    backtoPopup($(this).attr('id'));
                });

        $('body').append(modal);

        $(modal).modal({
            backdrop: false,
            keyboard: false,
            show: true
        });
        return true;
        /*}
         catch (error) {
         MyCookieJSErrors.Handle(error);
         return false;
         }*/
    };


    /**
     * Shows a popup with some content loaded from server     
     * @param {string} popupId The popup id
     * @param {string} moduleAction References module/submodule/action at MyCookie
     * @param {string} data Variables and values URL formatted     
     * @param {string} requestType Type of request: GET or POST(default)
     * @example MyCookieJS.showDynamicPopup('PU_News','news/edit','id=1','POST');
     */
    this.showDynamicPopup = function(popupId, moduleAction, data, requestType) {
        /*try {
         if (typeof (moduleAction) !== 'string') {
         throw 2;
         }  */
        requestType = (typeof (requestType) !== 'string') ? 'POST' : requestType;
        self.showWaitMessage();
        setTimeout(function() {
            $.ajax({
                async: true,
                type: requestType,
                url: self.mountURL(moduleAction, false),
                data: data,
                success: function(returning) {
                    self.closeWaitMessage();
                    self.showStaticPopup(popupId, returning);
                    $(popupId).attr('data-action', moduleAction).attr('data-data', data);
                }
            });
        }, 500);
        /*}
         catch (erro) {
         MyCookieJSErrors.Handle(erro);
         return false;
         }*/
    };

    this.refreshPopup = function(popupId) {
        popupId = String.format("#{0}", popupId);
        $(popupId).children('.modal-content').html(MyCookieJS.execute($(popupId).attr('data-action'), $(popupId).attr('data-data')));
    };


    /**
     * Atraves de uma requisicao HTTP, o servidor executa a funcao parametrizada
     * @param string moduloAcao: Referencia modulos, submodulos e acao no MyCookie
     * @param string dados: Variaveis e valores ordenados no formato de URL
     * @param bool retornar: Define se a funcao deve retornar o resultado da acao. Por padrao a funcao nao retornara qualquer resultado.
     * @param bool assincrono Define se a execucao da acao de ser assincrona. Por padrao a funcao e sincrona.
     * @param string tipoEncapsulamento: Define o tipo de encapsulmento dos dados: GET ou POST(padrao, se nulo)
     * @example _MyCookie.ExecutarAcao('noticias/deletar','id=1',true,'POST');      */
    this.execute = function(moduleAction, data, isAsync, onSuccess, requestType) {
        var returning;
        try {
            if (moduleAction === null) {
                throw 1;
            }
            isAsync = (typeof (isAsync) === 'boolean') ? isAsync : false;
            requestType = (typeof (requestType) === 'string') ? requestType : 'POST';
            $.ajax({
                async: isAsync,
                type: requestType,
                url: self.mountURL(moduleAction, false),
                data: data,
                success: function(executionReturn) {
                    returning = executionReturn;
                    if (typeof (onSuccess) === 'function') {
                        onSuccess(returning);
                    }
                }

            });
            return returning;
        }
        catch (error) {
            console.log(error);
            MyCookieJSErrors.Handle(error);
            return false;
        }
    }

    /**
     * Closes a popup
     * @param string popupName(id) Popup name to close, leave null if want to close actual
     * @example MyCookieJS.closePopup('MyPopup');
     */
    this.closePopup = function(popupName, isOpeningPopup) {
        openingPopup = (typeof (isOpeningPopup) === 'boolean') ? isOpeningPopup : false;
        popupName = (typeof (popupName) === 'string') ? popupName : $('.modal.in').attr('id');
        if (typeof (popupName) !== 'undefined') {
            idPopup = String.format('#{0}', popupName);
            $(idPopup).modal('hide');
        }
    };

    this.closeAllPopups = function() {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
    };

    this.gotoPopup = function(namePopup, onShown) {
        self.closePopup(null, true);
        fShown = (typeof (onShown) === 'function') ? onShown : function(e) {};
        setTimeout(function() {
            openingPopup = false;
            $(String.format('#{0}', namePopup)).on('shown.bs.modal', function(e) {
                fShown(e);
                $(String.format('#{0}', namePopup)).unbind('shown.bs.modal');
            }).modal('show');
        }, 700);
    };

    var backtoPopup = function(PopupAtual) {
        if (!openingPopup) {
            var idPopupAtual, PopupAnterior, idPopupAnterior;
            idPopupAtual = '#' + PopupAtual;
            PopupAnterior = $(idPopupAtual).children('.idPopupAnterior').html();
            idPopupAnterior = '#' + PopupAnterior;
            if (PopupAnterior !== '')
                $(idPopupAnterior).modal('show');
            else if ($('.modal.in').attr('id') == null)
                $('.modal-backdrop').remove();
        } else
            openingPopup = false;
    }

    /**
     * Redireciona o navegador para um modulo/submodulo/acao especificos      * @param string moduloAcao: Referencia modulos, submodulos e acao no MyCookie
     * @example _MyCookie.IrPara('administrador');
     */
    this.goto = function(moduleAction) {
        try {
            if (moduleAction === null)
                throw 3;
            location.href = self.mountURL(moduleAction, true);
        }
        catch (error) {
            MyCookieJSErrors.Handle(error);
            return false;
        }
    }

    this.showWaitMessage = function(msg) {
        msg = typeof (msg) === "string" ? msg : 'Obtendo dados do servidor...';
        self.showStaticPopup('aguarde-box', String.format('<div class="modal-header"><h4 class="modal-title">Aguarde <img src="{0}{1}" title="carregando" alt="carregando"></h4></div><div class="modal-body"><p>{2}</p></div>', self.getSite(), 'src/assets/images/loading1.gif', msg));
    }

    this.closeWaitMessage = function() {
        $('#aguarde-box').remove();
    }

    this.maskCEP = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/D/g, "")
            v = v.replace(/^(\d{5})(\d)/, "$1-$2")
            $(this).val(v)
        });
    }

    this.maskCNPJ = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/\D/g, "")
            v = v.replace(/^(\d{2})(\d)/, "$1.$2")
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2")
            v = v.replace(/(\d{4})(\d)/, "$1-$2")
            $(this).val(v)
        });
    }

    this.maskCPF = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/\D/g, "")
            v = v.replace(/(\d{3})(\d)/, "$1.$2")
            v = v.replace(/(\d{3})(\d)/, "$1.$2")
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
            $(this).val(v)
        });
    }

    this.maskDate_ptBR = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/\D/g, "")
            v = v.replace(/(\d{2})(\d)/, "$1/$2")
            v = v.replace(/(\d{2})(\d)/, "$1/$2")
            $(this).val(v)
        });
    }

    this.maskTime = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/\D/g, "")
            v = v.replace(/(\d{2})(\d)/, "$1:$2")
            $(this).val(v)
        });
    }
    this.maskMoney_ptBR = function(id) {
        $(id).bind('keypress', function(e) {
            var objTextBox = this;
            var SeparadorMilesimo = '.';
            var SeparadorDecimal = ',';
            var i = j = len = len2 = 0;
            var strCheck = '0123456789';
            var key, aux = aux2 = '';
            var whichCode = (window.Event) ? e.which : e.keyCode;
            if (whichCode == 13)
                return true;
            key = String.fromCharCode(whichCode); // Valor para o código da Chave
            //if (strCheck.indexOf(key) == -1) return false; // Chave inválida
            len = objTextBox.value.length;
            for (i = 0; i < len; i++)
                if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal))
                    break;
            aux = '';
            for (; i < len; i++)
                if (strCheck.indexOf(objTextBox.value.charAt(i)) != -1)
                    aux += objTextBox.value.charAt(i);
            if (strCheck.indexOf(key) != -1) // inclui aqui
                aux += key;
            len = aux.length;
            if (len == 0)
                objTextBox.value = '';
            if (len == 1)
                objTextBox.value = '0' + SeparadorDecimal + '0' + aux;
            if (len == 2)
                objTextBox.value = '0' + SeparadorDecimal + aux;
            if (len > 2) {
                aux2 = '';
                for (j = 0, i = len - 3; i >= 0; i--) {
                    if (j == 3) {
                        aux2 += SeparadorMilesimo;
                        j = 0;
                    }
                    aux2 += aux.charAt(i);
                    j++;
                }
                objTextBox.value = '';
                len2 = aux2.length;
                for (i = len2 - 1; i >= 0; i--)
                    objTextBox.value += aux2.charAt(i);
                objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
            }
            return false;
        });
        $(id).keypress();
    }

    this.maskPhone = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/\D/g, "")
            v = v.replace(/^(\d\d)(\d)/g, "($1) $2")
            v = v.replace(/(\d{4})(\d)/, "$1-$2")
            $(this).val(v)
        });
    }
    this.maskURL = function(id) {
        $(id).bind('keyup', function(e) {
            var v = $(this).val()
            v = v.replace(/^http:\/\/?/, "")
            dominio = v
            caminho = ''
            if (v.indexOf('/') > -1)
                dominio = v.split("/")[0]
            caminho = v.replace(/[^\/]*/, '')
            dominio = dominio.replace(/[^\w\.\+-:@]/g, '')
            caminho = caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g, '')
            caminho = caminho.replace(/([\?&])=/, "$1")
            if (caminho != "")
                dominio = dominio.replace(/\.+$/, '')
            v = "http://" + dominio + caminho
            $(this).val(v)
        });
    }

    this.getConfirmResult = function() {
        return confirmResult;
    }

    this.confirm = function(messageStr, onYes, onNo, closeAtConfirm) {
        var modalcontent, modalheader, header, modalbody, message, modalfooter, yesIcon, yesButton, noIcon, noButton;
        var title = MYCOOKIEJS_CONFIRMATION;
        closeAtConfirm = (typeof (closeAtConfirm) === 'boolean') ? closeAtConfirm : true;

        header = document.createElement('h4');
        $(header).append(title);

        modalheader = document.createElement('div');
        $(modalheader)
                .addClass('modal-header')
                .append(header);

        message = document.createElement('p');
        $(message).css('text-align', 'justify');
        $(message).append(messageStr);

        modalbody = document.createElement('div');
        $(modalbody)
                .addClass('modal-body')
                .append(message);

        yesIcon = document.createElement('i');
        $(yesIcon).addClass('fa').addClass('fa-thumbs-up');

        yesButton = document.createElement('button');
        $(yesButton)
                .addClass('btn btn-success')
                .append(yesIcon)
                .append(' ' + MYCOOKIEJS_YES)
                .click(function() {
                    confirmResult = true;
                    if (typeof (onYes) === 'function') {
                        onYes();
                    }
                    if (closeAtConfirm) {
                        MyCookieJS.closePopup('ConfirmBS');
                        setTimeout(function() {
                            $('#ConfirmBS').remove();
                        }, 500);
                    }
                });

        noIcon = document.createElement('i');
        $(noIcon).addClass('fa').addClass('fa-thumbs-down');

        noButton = document.createElement('button');
        $(noButton)
                .addClass('btn btn-danger')
                .append(noIcon)
                .append(' ' + MYCOOKIEJS_NO)
                .click(function() {
                    confirmResult = false;
                    if (typeof (onNo) === 'function') {
                        onNo();
                    }
                    if (closeAtConfirm) {
                        MyCookieJS.closePopup('ConfirmBS');
                        setTimeout(function() {
                            $('#ConfirmBS').remove();
                        }, 500);
                    }
                });

        modalfooter = document.createElement('div');
        $(modalfooter)
                .addClass('modal-footer')
                .css('text-align', 'center')
                .append(yesButton)
                .append(noButton);

        modalcontent = document.createElement('div');
        $(modalcontent)
                .append(modalheader)
                .append(modalbody)
                .append(modalfooter);

        MyCookieJS.showStaticPopup('ConfirmBS', modalcontent, function() {
            $(yesButton).focus();
        });
    };

    this.alert = function(messageStr, gotoPopup) {
        var modalcontent, modalheader, header, modalbody, message, modalfooter, okButton;
        title = MYCOOKIEJS_ALERT;

        header = document.createElement('h4');
        $(header).append(title);

        modalheader = document.createElement('div');
        $(modalheader)
                .addClass('modal-header')
                .append(header);

        message = document.createElement('p');
        $(message)
                .css('text-align', 'justify')
                .append(messageStr);

        modalbody = document.createElement('div');
        $(modalbody)
                .addClass('modal-body')
                .append(message);

        okButton = document.createElement('button');
        $(okButton)
                .addClass('btn btn-primary')
                .append('OK');
        switch (typeof (gotoPopup)) {
            case 'boolean':
                $(okButton).click(function() {
                    MyCookieJS.closeAllPopups();
                });
                break;
            case 'string':
                $(okButton).click(function() {
                    MyCookieJS.gotoPopup(gotoPopup);
                });
                break;
            case 'function':
                $(okButton).click(gotoPopup);
                break;
            default :
                $(okButton).click(function() {
                    MyCookieJS.closePopup('AlertBS');
                });
                break;
        }

        modalfooter = document.createElement('div');
        $(modalfooter)
                .addClass('modal-footer')
                .css('text-align', 'center')
                .append(okButton);

        modalcontent = document.createElement('div');
        $(modalcontent)
                .append(modalheader)
                .append(modalbody)
                .append(modalfooter);

        MyCookieJS.showStaticPopup('AlertBS', modalcontent, function() {
            $(okButton).focus();
        });
    };

}

function TMyCookieJSErrors() {
    this.Handle = function(erro) {
        switch (erro) {
            case 1:
                alert('ERR-JS-001: Erro na parametrização de TMyCookieJS.ExecutarAcao');
                break;
            case 2:
                alert('ERR-JS-002: Erro na parametrização de TMyCookieJS.AbrirPopup');
                break;
        }
    }
}

if (!String.format) {
    String.format = function(format) {
        var args = Array.prototype.slice.call(arguments, 1);
        return format.replace(/{(\d+)}/g, function(match, number) {
            return typeof args[number] != 'undefined'
                    ? args[number]
                    : match
                    ;
        });
    };
}

var MyCookieJS = new TMyCookieJS();