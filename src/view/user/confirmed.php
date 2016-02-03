<div class="modal" id="mdConfirmed">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" data-i18n="user:confirmed.title"></h4>
            </div>            
            <div class="modal-body">                                                        
                <p data-i18n="user:confirmed.line_1"></p>
                <p data-i18n="user:confirmed.line_2"></p>
            </div>                
            <div class="modal-footer">                
                <button type="button" class="btn btn-success" onclick="location.href = '<?php echo $data ?>'"><i class="fa fa-sign-in"></i> <span data-i18n="user:confirmed.button"></span></button>
            </div>            
        </div>
    </div>
</div>
<script type="text/javascript">
    require(['jquery'], function ($) {
        $(function () {
            $('#mdConfirmed').modal({
                'show': true,
                'keyboard': false,
                'backdrop': false
            });
        });
    });
</script>