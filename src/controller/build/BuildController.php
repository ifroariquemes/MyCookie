<?php

namespace controller\build;

class BuildController {

    const RJSFile = 'components/r.js';
    const BuildJSFile = 'components/build-js.js';
    const BuildCSSFile = 'components/build-css.js';
    const BuildJSConfigFile = 'components/build-config.js';
    const BuildCSSConfigFile = 'components/build-config.css';
    const SourceJS = 'src/assets/js';
    const SourceCSS = 'src/assets/css';
    const SourceViews = 'src/view';
    const SourceLang = 'src/lang';

    public function Build() {
        global $_Async;
        $_Async = true;
        include('src/view/build/build.php');
    }

    public function CreateBuildJS() {
        /* @var $_MyCookie \lib\MyCookie */

        global $_MyCookie;
        if ($this->CheckPassword()) {
            $jsFilesId = array();
            $myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();
            unlink(BuildController::BuildJSFile);
            $fp = fopen(BuildController::BuildJSFile, 'w+');
            fwrite($fp, "({\n");
            fwrite($fp, "   baseUrl: \"../\",\n");
            fwrite($fp, "   paths: {\n");
            fwrite($fp, "       mycookie: \"components/mycookie\",\n");
            array_push($jsFilesId, 'mycookie');
            if ($myCookieConfiguration->build->use_jquery) {
                fwrite($fp, "       jquery: \"vendor/sheillendra/metro-bootstrap/docs/jquery-1.8.0\",\n");
                array_push($jsFilesId, 'jquery');
            }
            if ($myCookieConfiguration->build->use_bootstrap) {
                if ($myCookieConfiguration->build->use_metro) {
                    fwrite($fp, "       bootstrap: \"vendor/sheillendra/metro-bootstrap/docs/bootstrap\",\n");
                    fwrite($fp, "       application: \"vendor/sheillendra/metro-bootstrap/docs/application\",\n");
                    array_push($jsFilesId, 'bootstrap', 'application');
                } else {
                    fwrite($fp, "       bootstrap: \"vendor/twbs/bootstrap/dist/js/bootstrap\",\n");
                    array_push($jsFilesId, 'bootstrap');
                }
            }
            foreach ($this->SeekFiles(BuildController::SourceJS, 'js') as $js) {
                $filePath = explode('.', $js);
                $fileName = explode('/', $filePath[0]);
                array_push($jsFilesId, sprintf('%s_%s', array_pop($fileName), array_pop($fileName)));
                fwrite($fp, sprintf("       %s: \"%s\",\n", end($jsFilesId), $filePath[0]));
            }
            fwrite($fp, "   },\n");
            fwrite($fp, "   name: \"components/build-config\",\n");
            fwrite($fp, "   out: \"bundle.js\"\n");
            fwrite($fp, "})");
            fclose($fp);
            $this->CreateBuildJSConfig($jsFilesId);
        } else {
            echo 'The password does\'t match';
        }
    }

    private function CreateBuildJSConfig($jsFilesId) {
        if ($this->CheckPassword()) {
            unlink(BuildController::BuildJSConfigFile);
            $fp = fopen(BuildController::BuildJSConfigFile, 'w+');
            fwrite($fp, 'require([');
            foreach ($jsFilesId as $jsId) {
                fwrite($fp, "'$jsId',");
            }
            fwrite($fp, '], function() { });');
            fclose($fp);
        } else {
            echo 'The password does\'t match';
        }
    }

    public function CleanCache() {
        unlink(BuildController::BuildCSSConfigFile);
        unlink(BuildController::BuildCSSFile);
        unlink(BuildController::BuildJSConfigFile);
        unlink(BuildController::BuildJSFile);
        array_map('unlink', glob('cache/*'));
    }

    private function SeekFiles($path, $extension = '*') {
        if (is_file($path)) {
            return $path;
        }
        if (is_dir($path)) {
            $files = array();
            $fHandle = opendir($path);
            while (false !== ($file = readdir($fHandle))) {
                if ($file !== '.' && $file !== '..') {
                    $fileFound = $this->SeekFiles("$path/$file");
                    if (is_array($fileFound)) {
                        foreach ($fileFound as $newFile) {
                            $fileInfo = explode('.', $newFile);
                            if ($extension == '*' || $extension == end($fileInfo)) {
                                array_push($files, $newFile);
                            }
                        }
                    } else {
                        $fileInfo = explode('.', $fileFound);
                        if ($extension == '*' || $extension == end($fileInfo)) {
                            array_push($files, $fileFound);
                        }
                    }
                }
            }
            closedir($fHandle);
            return $files;
        }
    }

    public function CreateBuildCSS() {
        if ($this->CheckPassword()) {
            unlink(BuildController::BuildCSSFile);
            $fp = fopen(BuildController::BuildCSSFile, 'w+');
            fwrite($fp, "({\n");
            fwrite($fp, "   cssIn: \"build-config.css\",\n");
            fwrite($fp, "   out: \"bundle.css\",\n");
            fwrite($fp, "   optimizeCss: \"default\"\n");
            fwrite($fp, "})");
            fclose($fp);
            $this->CreateBuildCSSConfig();
        }
    }

    private function CreateBuildCSSConfig() {
        /* @var $_MyCookie \lib\MyCookie */
        global $_MyCookie;
        if ($this->CheckPassword()) {
            $myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();
            unlink(BuildController::BuildCSSConfigFile);
            $fp = fopen(BuildController::BuildCSSConfigFile, 'w+');
            if ($myCookieConfiguration->build->use_bootstrap) {
                if ($myCookieConfiguration->build->use_metro) {
                    fwrite($fp, "@import url('../vendor/sheillendra/metro-bootstrap/css/metro-bootstrap.css');");
                    fwrite($fp, "\n@import url('../vendor/sheillendra/metro-bootstrap/docs/font-awesome.css');");
                } else {
                    fwrite($fp, "@import url('../vendor/twbs/bootstrap/dist/css/bootstrap.css');");
                    fwrite($fp, "\n@import url('../vendor/twbs/bootstrap/dist/css/bootstrap-theme.css');");
                }
            }
            foreach ($this->SeekFiles(BuildController::SourceCSS, 'css') as $css) {
                fwrite($fp, "\n@import url('../$css');");
            }
            fclose($fp);
        } else {
            echo 'The password does\'t match';
        }
    }

    public function BuildJS() {
        $check = 0;
        if ($this->CheckPassword()) {
            system(sprintf("node %s -o %s optimize=none", BuildController::RJSFile, BuildController::BuildJSFile), $check);
            if ($check !== 0) {
                system(sprintf("nodejs %s -o %s", BuildController::RJSFile, BuildController::BuildJSFile), $check);
                if ($check !== 0) {
                    include('src/view/build/node-run.php');
                }
            }
        } else {
            echo 'The password does\'t match';
        }
    }

    public function BuildCSS() {
        $check = 0;
        if ($this->CheckPassword()) {
            system(sprintf("node %s -o %s", BuildController::RJSFile, BuildController::BuildCSSFile), $check);
            if ($check !== 0) {
                system(sprintf("nodejs %s -o %s", BuildController::RJSFile, BuildController::BuildCSSFile), $check);
                if ($check !== 0) {
                    include('src/view/build/node-run.php');
                }
            }
        } else {
            echo 'The password does\'t match';
        }
    }

    public function UpdateSchema() {
        /* @var $_EntityManager \Doctrine\ORM\EntityManager */
        global $_EntityManager;
        if ($this->CheckPassword()) {
            try {
                $_EntityManager->beginTransaction();
                $_EntityManager->close();
                system('php vendor/bin/doctrine orm:schema-tool:update --force');
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            echo _e("The password doesn't match", 'build');
        }
    }

    public function RecreateSchema() {
        if ($this->CheckPassword()) {
            system('php vendor/bin/doctrine orm:schema-tool:drop --force');
            system('php vendor/bin/doctrine orm:schema-tool:create');
        } else {
            echo 'The password does\'t match';
        }
    }

    public function CheckPassword() {
        /* @var $_MyCookie \lib\MyCookie */
        global $_MyCookie;
        $myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();
        return filter_input(INPUT_POST, 'password') === md5($myCookieConfiguration->build->password);
    }

    public function CheckPasswordRet() {
        var_export($this->CheckPassword());
    }

    /**
     * If not generating, please check permissions
     */
    public function GeneratePortableObjects() {
        if ($this->CheckPassword()) {
            $fHandle = opendir(BuildController::SourceViews);
            array_map('unlink', glob(sprintf('%s/_po/*', BuildController::SourceLang)));
            while (($file = readdir($fHandle)) !== false) {
                if ($file !== '.' && $file !== '..') {
                    if (count(scandir(sprintf('%s/%s', BuildController::SourceViews, $file))) > 2) {
                        system(sprintf('xgettext -d %s -p %s/_po -k__ -k_e -k_n:1,2 -k_en:1,2 %s/%s/*.php', $file, BuildController::SourceLang, BuildController::SourceViews, $file), $check);
                        if ($check !== 0) {
                            system(sprintf('gettext -d %s -p %s/_po -k__ -k_e -k_n:1,2 -k_en:1,2 %s/%s/*.php', $file, BuildController::SourceLang, BuildController::SourceViews, $file), $check);
                            if ($check !== 0) {
                                include('src/build/view/xgettext-run.php');
                                return;
                            }
                        }
                        $fileName = sprintf('%s/_po/%s.po', BuildController::SourceLang, $file);
                        if (file_exists($fileName)) {
                            $c = file_get_contents($fileName);
                            $c = str_replace('charset=CHARSET', 'charset=UTF-8', $c);
                            file_put_contents($fileName, $c);
                        }
                    }
                }
            }
            echo 'OK';
        }
    }

    /**
     * If not generating, please check permissions
     */
    public function GenerateMachineObjects() {
        if ($this->CheckPassword()) {
            array_map('unlink', glob(sprintf('%s/*.mo', BuildController::SourceLang)));
            foreach ($this->SeekFiles(BuildController::SourceLang, 'po') as $po) {
                if (strpos($po, '_po') === false) {
                    $filePath = explode('.', $po);
                    system(sprintf('msgfmt %s -o %s.mo', $po, $filePath[0]), $check);
                    if ($check > 1) {
                        include('src/build/view/msgfmt-run.php');
                        return;
                    }
                }
            }
            echo 'OK';
        }
    }

}
