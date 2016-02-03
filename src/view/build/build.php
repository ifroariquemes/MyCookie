<?php
global $_MyCookie;
global $_BaseURL;
global $_Config;
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title data-i18n="build:title">Building bundle (JS/CSS)</title>
        <?php $_MyCookie->useStyle('vendor/sheillendra/metro-bootstrap/css/metro-bootstrap.css') ?>
        <?php $_MyCookie->useStyle('vendor/sheillendra/metro-bootstrap/docs/font-awesome.css') ?>        
        <?php $_MyCookie->RequireJS() ?> 
    </head>
    <body>        
        <div class="container hidden">    
            <div class="row">
                <div class="col-lg-12">
                    <h1 data-i18n="build:header.title">Building application</h1>
                    <h4 data-i18n="build:header.description">Bundle all JS/CSS from application and update database schema</h4>
                    <h4 class="text-warning" data-i18n="build:header.warning">PS: it can take some time depending on files size</h4>                
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <p class="step1"><span data-i18n="build:step.one">1 - Creating JS build file</span>...
                        <?php $_MyCookie->image('src/assets/images/loading1.gif', 'step1') ?>                        
                        <i class="step1 fa fa-check hidden"></i>
                    </p>
                    <p class="step2 hidden"><span data-i18n="build:step.two">2 - Creating CSS build file</span>...
                        <?php $_MyCookie->image('src/assets/images/loading1.gif', 'step2') ?>
                        <i class="step2 fa fa-check hidden"></i>
                    </p>
                    <p class="step3 hidden"><span data-i18n="build:step.three">3 - Building JS bundle</span>...
                        <?php $_MyCookie->image('src/assets/images/loading1.gif', 'step3') ?>
                        <i class="step3 fa fa-ambulance hidden"></i>
                        <i class="step3 fa fa-check hidden"></i>
                    </p>
                    <p class="step4 hidden"><span data-i18n="build:step.four">4 - Building CSS bundle</span>...
                        <?php $_MyCookie->image('src/assets/images/loading1.gif', 'step4') ?>
                        <i class="step4 fa fa-ambulance hidden"></i>
                        <i class="step4 fa fa-check hidden"></i>
                    </p>
                    <p class="step5 hidden"><span data-i18n="build:step.five">5 - Updating database schema</span>...
                        <?php $_MyCookie->image('src/assets/images/loading1.gif', 'step5') ?>
                        <i class="step5 fa fa-ambulance hidden"></i>
                        <i class="step5 fa fa-check hidden"></i>
                        <button type="button" class="btn btn-sm btn-warning step5 hidden" onclick="Recreate()">Re-create database</button>
                    </p>   
                    <p class="step-cache hidden"><span data-i18n="build:step.six">6 - Cleaning cache</span>...
                        <?php $_MyCookie->image('src/assets/images/loading1.gif', 'step-cache') ?>
                        <i class="step-cache fa fa-ambulance hidden"></i>
                        <i class="step-cache fa fa-check hidden"></i>                        
                    </p>
                    <div class="done hidden row alert-success">
                        <div class="col-md-12">
                            <span data-i18n="build:step.done">Done!</span> <a href="<?= $_BaseURL ?>">Go to start.</a>
                        </div>
                    </div>    
                </div>
                <div class="col-lg-6">
                    <div class="jsoutput hidden">
                        <h4 class="small" data-i18n="build:output.js">JS output</h4>
                        <pre></pre>
                    </div>
                    <div class="cssoutput hidden">
                        <h4 class="small" data-i18n="build:output.css">CSS output</h4>
                        <pre></pre>
                    </div>                  
                    <div class="ormoutput hidden">
                        <h4 class="small" data-i18n="build:output.orm">ORM output</h4>
                        <pre></pre>
                    </div>
                </div>
            </div>
        </div>   
        <?php $_MyCookie->useScript('components/jquery.js') ?>
        <?php $_MyCookie->useScript('components/mycookie.js') ?> 
        <?php $_MyCookie->useScript('components/i18next.js') ?>                
        <?php $_MyCookie->useScript('components/i18next.config.js') ?>                        
        <?php $_MyCookie->useScript('src/assets/js/build/jeffmott/criptojs/md5.js') ?>                
        <script type="text/javascript">
            var password = "";
            var MYCOOKIEJS_BASEURL = '<?= $_BaseURL ?>';

            function Start() {
                password = CryptoJS.MD5(prompt(i18n.t('build:message.password'))).toString();
                MyCookieJS.execute('build/CheckPasswordRet', 'password=' + password, true, function (ret) {
                    if (ret === 'false')
                        alert(i18n.t('build:message.bad_password'));
                    else {
                        $('.container').removeClass('hidden');
                        Step1();
                    }
                });
            }

            function Step1() {
                MyCookieJS.execute('build/CreateBuildJS', "password=" + password, true, function (ret) {
                    $('img.step1').addClass('hidden');
                    $('i.step1').removeClass('hidden');
                    $('p.step2').removeClass('hidden');
                    Step2();
                });
            }

            function Step2() {
                MyCookieJS.execute('build/CreateBuildCSS', 'password=' + password, true, function (ret) {
                    $('img.step2').addClass('hidden');
                    $('i.step2').removeClass('hidden');
                    $('p.step3').removeClass('hidden');
                    Step3();
                });
            }

            function Step3() {
                MyCookieJS.execute('build/BuildJS', 'password=' + password, true, function (ret) {
                    $('.jsoutput').removeClass('hidden').children('pre').html(ret);
                    $('img.step3').addClass('hidden');
                    $('p.step4').removeClass('hidden');
                    if (ret.substring(0, 3) === 'ERR') {
                        $('p.step3').addClass('text-danger');
                        $('i.step3.fa-ambulance').removeClass('hidden');
                    }
                    else
                        $('i.step3.fa-check').removeClass('hidden');
                    Step4();
                });
            }

            function Step4() {
                MyCookieJS.execute('build/BuildCSS', 'password=' + password, true, function (ret) {
                    $('.cssoutput').removeClass('hidden').children('pre').html(ret);
                    $('img.step4').addClass('hidden');
                    $('p.step5').removeClass('hidden');
                    if (ret.substring(0, 3) === 'ERR') {
                        $('p.step4').addClass('text-danger');
                        $('i.step4.fa-ambulance').removeClass('hidden');
                    }
                    else
                        $('i.step4.fa-check').removeClass('hidden');
                    Step5();
                });
            }

            function Step5() {
                MyCookieJS.execute('build/UpdateSchema', 'password=' + password, true, function (ret) {
                    $('.ormoutput').removeClass('hidden').children('pre').html(ret);
                    $('img.step5').addClass('hidden');
                    $('p.step-cache').removeClass('hidden');
                    if (ret.substring(0, 8) === 'SQLSTATE') {
                        $('p.step5').addClass('text-danger');
                        $('i.step5.fa-ambulance').removeClass('hidden');
                    }
                    else {
                        $('i.step5.fa-check').removeClass('hidden');
                        $('button.step5').removeClass('hidden');
                    }
                    StepCache();
                });
            }

            function StepCache() {
                MyCookieJS.execute('build/CleanCache', 'password=' + password, true, function (ret) {
                    $('.done').removeClass('hidden');
                    $('img.step-cache').addClass('hidden');
                    $('i.step-cache.fa-check').removeClass('hidden');
                });
                localStorage.removeItem('res_dev');
                localStorage.removeItem('res_<?= $_Config->lang ?>');
            }

            function Recreate() {
                if (confirm(i18n.t('build:message.recreate'))) {
                    $('img.step5').removeClass('hidden');
                    $('i.step5.fa-ambulance').addClass('hidden');
                    $('i.step5.fa-check').addClass('hidden');
                    $('button.step5').addClass('hidden');
                    MyCookieJS.execute('build/RecreateSchema', 'password=' + password, true, function (ret) {
                        $('i.step5.fa-check').removeClass('hidden');
                        $('img.step5').addClass('hidden');
                        $('.ormoutput').children('pre').append('------------------------<br>'+ret);
                        alert(i18n.t('build:message.recreated'));                        
                    });
                }
            }

            function checkTranslate() {
                setTimeout(function () {
                    if (!i18n.isInitialized())
                        checkTranslate();
                    else
                        Start();
                }, 500);
            }

            checkTranslate();
        </script>        
    </body>
</html>
