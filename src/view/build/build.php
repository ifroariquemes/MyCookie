<html>
    <head>
        <meta charset="UTF-8">
        <title>Building bundle (JS/CSS)</title>
        <link rel="stylesheet" type="text/css" href="vendor/sheillendra/metro-bootstrap/css/metro-bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="vendor/sheillendra/metro-bootstrap/docs/font-awesome.css" />        
    </head>
    <body>
        <div class="container hidden">    
            <div class="row">
                <div class="col-lg-12">
                    <h1><?php _e('Building application', 'build') ?></h1>
                    <h4><?php _e('Bundle all JS/CSS from application and update database schema', 'build') ?></h4>
                    <h4 class="text-warning"><?php _e('PS: it can take some time depending on files size', 'build') ?></h4>                
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <p class="step1">1 - <?php _e('Creating JS build file', 'build') ?>...
                        <img class="step1" src="src/assets/images/loading1.gif" />
                        <i class="step1 fa fa-check hidden"></i>
                    </p>
                    <p class="step2 hidden">2 - <?php _e('Creating CSS build file', 'build') ?>...
                        <img class="step2" src="src/assets/images/loading1.gif" />
                        <i class="step2 fa fa-check hidden"></i>
                    </p>
                    <p class="step3 hidden">3 - <?php _e('Building JS bundle', 'build') ?>...
                        <img class="step3" src="src/assets/images/loading1.gif" />
                        <i class="step3 fa fa-ambulance hidden"></i>
                        <i class="step3 fa fa-check hidden"></i>
                    </p>
                    <p class="step4 hidden">4 - <?php _e('Building CSS bundle', 'build') ?>...
                        <img class="step4" src="src/assets/images/loading1.gif" />
                        <i class="step4 fa fa-ambulance hidden"></i>
                        <i class="step4 fa fa-check hidden"></i>
                    <p>
                    <p class="step-xg hidden">5 - <?php _e('Generating Portable Objects (for I18n)', 'build') ?>...
                        <img class="step-xg" src="src/assets/images/loading1.gif" />
                        <i class="step-xg fa fa-ambulance hidden"></i>
                        <i class="step-xg fa fa-check hidden"></i>
                    <p>
                    <p class="step-mf hidden">5 - <?php _e('Generating Machine Objects (for I18n)', 'build') ?>...
                        <img class="step-mf" src="src/assets/images/loading1.gif" />
                        <i class="step-mf fa fa-ambulance hidden"></i>
                        <i class="step-mf fa fa-check hidden"></i>
                    <p>
                    <p class="step5 hidden">6 - <?php _e('Updating database schema', 'build') ?>...
                        <img class="step5" src="src/assets/images/loading1.gif" />
                        <i class="step5 fa fa-ambulance hidden"></i>
                        <i class="step5 fa fa-check hidden"></i>
                        <button type="button" class="btn btn-sm btn-warning step5 hidden" onclick="Recreate()"><?php _e('Re-create database', 'build') ?></button>
                    </p>   
                    <p class="done hidden text-success"><?php _e('Done!', 'build') ?></p>    
                </div>
                <div class="col-lg-6">
                    <div class="jsoutput hidden">
                        <h4 class="small"><?php _e('JS output', 'build') ?>:</h4>
                        <pre></pre>
                    </div>
                    <div class="cssoutput hidden">
                        <h4 class="small"><?php _e('CSS output', 'build') ?>:</h4>
                        <pre></pre>
                    </div>
                    <div class="xgoutput hidden">
                        <h4 class="small"><?php _e('xGetText output', 'build') ?>:</h4>
                        <pre></pre>
                    </div>
                    <div class="mfoutput hidden">
                        <h4 class="small"><?php _e('MsgFmt output', 'build') ?>:</h4>
                        <pre></pre>
                    </div>
                    <div class="ormoutput hidden">
                        <h4 class="small"><?php _e('ORM output', 'build') ?>:</h4>
                        <pre></pre>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="vendor/sheillendra/metro-bootstrap/docs/jquery-1.8.0.js"></script>
        <script type="text/javascript" src="components/jeffmott/criptojs/md5.js"></script>
        <script type="text/javascript">
                            var password = "";

                            function Start() {
                                password = CryptoJS.MD5(prompt("<?php _e('Enter the building password', 'build') ?>:")).toString();
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/CheckPasswordRet?async',
                                    data: "password=" + password,
                                    success: function(ret) {
                                        if (ret === 'false')
                                            alert("<?php _e("The password doesn't match", 'build') ?>");
                                        else {
                                            $('.container').removeClass('hidden');
                                            Step1();
                                        }
                                    }
                                });
                            }

                            function Step1() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/CreateBuildJS?async',
                                    data: "password=" + password,
                                    success: function() {
                                        $('img.step1').addClass('hidden');
                                        $('i.step1').removeClass('hidden');
                                        $('p.step2').removeClass('hidden');
                                        Step2();
                                    }
                                });
                            }

                            function Step2() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/CreateBuildCSS?async',
                                    data: "password=" + password,
                                    success: function() {
                                        $('img.step2').addClass('hidden');
                                        $('i.step2').removeClass('hidden');
                                        $('p.step3').removeClass('hidden');
                                        Step3();
                                    }
                                });
                            }

                            function Step3() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/BuildJS?async',
                                    data: "password=" + password,
                                    success: function(ret) {
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
                                    }
                                });
                            }

                            function Step4() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/BuildCSS?async',
                                    data: "password=" + password,
                                    success: function(ret) {
                                        $('.cssoutput').removeClass('hidden').children('pre').html(ret);
                                        $('img.step4').addClass('hidden');
                                        $('p.step-xg').removeClass('hidden');
                                        if (ret.substring(0, 3) === 'ERR') {
                                            $('p.step4').addClass('text-danger');
                                            $('i.step4.fa-ambulance').removeClass('hidden');
                                        }
                                        else
                                            $('i.step4.fa-check').removeClass('hidden');
                                        StepXG();
                                    }
                                });
                            }

                            function StepXG() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/GeneratePortableObjects?async',
                                    data: "password=" + password,
                                    success: function(ret) {
                                        $('.xgoutput').removeClass('hidden').children('pre').html(ret);
                                        $('img.step-xg').addClass('hidden');
                                        $('p.step-mf').removeClass('hidden');
                                        if (ret.substring(0, 3) === 'ERR') {
                                            $('p.step-xg').addClass('text-danger');
                                            $('i.step-xg.fa-ambulance').removeClass('hidden');
                                        }
                                        else
                                            $('i.step-xg.fa-check').removeClass('hidden');
                                        StepMF();
                                    }
                                });
                            }
                            
                            function StepMF() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/GenerateMachineObjects?async',
                                    data: "password=" + password,
                                    success: function(ret) {
                                        $('.mfoutput').removeClass('hidden').children('pre').html(ret);
                                        $('img.step-mf').addClass('hidden');
                                        $('p.step5').removeClass('hidden');
                                        if (ret.substring(0, 3) === 'ERR') {
                                            $('p.step-mf').addClass('text-danger');
                                            $('i.step-mf.fa-ambulance').removeClass('hidden');
                                        }
                                        else
                                            $('i.step-mf.fa-check').removeClass('hidden');
                                        Step5();
                                    }
                                });
                            }

                            function Step5() {
                                $.ajax({
                                    type: 'POST',
                                    url: 'build/UpdateSchema?async',
                                    data: "password=" + password,
                                    success: function(ret) {
                                        $('.ormoutput').removeClass('hidden').children('pre').html(ret);
                                        $('img.step5').addClass('hidden');
                                        $('p.done').removeClass('hidden');
                                        if (ret.substring(0, 8) === 'SQLSTATE') {
                                            $('p.step5').addClass('text-danger');
                                            $('i.step5.fa-ambulance').removeClass('hidden');
                                        }
                                        else {
                                            $('i.step5.fa-check').removeClass('hidden');
                                            $('button.step5').removeClass('hidden');
                                        }
                                    }
                                });
                            }

                            function Recreate() {
                                if (confirm('<?php _e('Do you really want to recreate database? (It will erase EVERYTHING!)', 'build') ?>')) {
                                    $('img.step5').removeClass('hidden');
                                    $('i.step5.fa-ambulance').addClass('hidden');
                                    $('i.step5.fa-check').addClass('hidden');
                                    $('button.step5').addClass('hidden');
                                    $.ajax({
                                        type: 'POST',
                                        url: 'build/RecreateSchema?async',
                                        data: "password=" + password,
                                        success: function() {
                                            $('i.step5.fa-check').removeClass('hidden');
                                            $('img.step5').addClass('hidden');
                                            alert('<?php _e('Your database was recreated.', 'build') ?>');
                                        }
                                    });
                                }
                            }

                            $(function() {
                                Start();
                            });
        </script>
    </body>
</html>
