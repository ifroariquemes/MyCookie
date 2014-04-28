<?php

namespace controller\build;

class BuildControl {

    const RJSFile = 'components/build/r.js';
    const BuildJSFile = 'components/build/build-js.js';
    const BuildCSSFile = 'components/build/build-css.js';
    const BuildJSConfigFile = 'components/build/build-config.js';
    const BuildCSSConfigFile = 'components/build/build-config.css';
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
            $fp = fopen(BuildControl::BuildJSFile, 'w+');
            fwrite($fp, "({\n");
            fwrite($fp, "   baseUrl: \"../\",\n");
            fwrite($fp, "   paths: {\n");
            if ($myCookieConfiguration->build->use_jquery) {
                fwrite($fp, "       jquery: \"../vendor/sheillendra/metro-bootstrap/docs/jquery-1.8.0\",\n");
                array_push($jsFilesId, 'jquery');
            }
            if ($myCookieConfiguration->build->use_bootstrap) {
                if ($myCookieConfiguration->build->use_metro) {
                    fwrite($fp, "       bootstrap: \"../vendor/sheillendra/metro-bootstrap/docs/bootstrap\",\n");
                    fwrite($fp, "       application: \"../vendor/sheillendra/metro-bootstrap/docs/application\",\n");
                    array_push($jsFilesId, 'bootstrap', 'application');
                } else {
                    fwrite($fp, "       bootstrap: \"../vendor/twbs/bootstrap/dist/js/bootstrap\",\n");
                    array_push($jsFilesId, 'bootstrap');
                }
            }
            foreach ($this->SeekFiles(BuildControl::SourceJS, 'js') as $js) {
                $filePath = explode('.', $js);
                $fileName = explode('/', $filePath[0]);
                array_push($jsFilesId, sprintf('%s_%s', array_pop($fileName), array_pop($fileName)));
                fwrite($fp, sprintf("       %s: \"../%s\",\n", end($jsFilesId), $filePath[0]));
            }
            fwrite($fp, "   },\n");
            fwrite($fp, "   name: \"build/build-config\",\n");
            fwrite($fp, "   out: \"../bundle.js\"\n");
            fwrite($fp, "})");
            fclose($fp);
            $this->CreateBuildJSConfig($jsFilesId);
        } else {
            echo 'The password does\'t match';
        }
    }

    private function CreateBuildJSConfig($jsFilesId) {
        if ($this->CheckPassword()) {
            $fp = fopen(BuildControl::BuildJSConfigFile, 'w+');
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
        /* @var $_MyCookie \lib\MyCookie */
        global $_MyCookie;
        if ($this->CheckPassword()) {
            $myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();
            $fp = fopen(BuildControl::BuildCSSConfigFile, 'w+');
            if ($myCookieConfiguration->build->use_bootstrap) {
                if ($myCookieConfiguration->build->use_metro) {
                    fwrite($fp, "@import url('../../vendor/sheillendra/metro-bootstrap/css/metro-bootstrap.css');");
                    fwrite($fp, "\n@import url('../../vendor/sheillendra/metro-bootstrap/docs/font-awesome.css');");
                } else {
                    fwrite($fp, "@import url('../../vendor/twbs/bootstrap/dist/css/bootstrap.css');");
                    fwrite($fp, "\n@import url('../../vendor/twbs/bootstrap/dist/css/bootstrap-theme.css');");
                }
            }
            foreach ($this->SeekFiles(BuildControl::SourceCSS, 'css') as $css) {
                fwrite($fp, "\n@import url('../../$css');");
            }
            fclose($fp);
        } else {
            echo 'The password does\'t match';
        }
    }

    public function BuildJS() {
        $check = 0;
        if ($this->CheckPassword()) {
            system(sprintf("node %s -o %s", BuildControl::RJSFile, BuildControl::BuildJSFile), $check);
            if ($check !== 0) {
                system(sprintf("nodejs %s -o %s", BuildControl::RJSFile, BuildControl::BuildJSFile), $check);
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
            system(sprintf("node %s -o %s", BuildControl::RJSFile, BuildControl::BuildCSSFile), $check);
            if ($check !== 0) {
                system(sprintf("nodejs %s -o %s", BuildControl::RJSFile, BuildControl::BuildCSSFile), $check);
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
            $fHandle = opendir(BuildControl::SourceViews);
            while (($file = readdir($fHandle)) !== false) {
                if ($file !== '.' && $file !== '..') {
                    if (count(scandir(sprintf('%s/%s', BuildControl::SourceViews, $file))) > 2) {
                        system(sprintf('xgettext -d %s -p %s/_po -k__ -k_e -k_n:1,2 -k_en:1,2 %s/%s/*.php', $file, BuildControl::SourceLang, BuildControl::SourceViews, $file), $check);
                        if ($check !== 0) {
                            system(sprintf('gettext -d %s -p %s_po -k__ -k_e -k_n:1,2 -k_en:1,2 %s/%s/*.php', $file, BuildControl::SourceLang, BuildControl::SourceViews, $file), $check);
                            if ($check !== 0) {
                                include('src/build/view/xgettext-run.php');
                                return;
                            }
                        }
                        $fileName = sprintf('%s/_po/%s.po', BuildControl::SourceLang, $file);
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
            foreach ($this->SeekFiles(BuildControl::SourceLang, 'po') as $po) {
                if (strpos($po, '_po') === false) {
                    $filePath = explode('.', $po);                    
                    system(sprintf('msgfmt %s -o %s.mo', $po, $filePath[0]), $check);
                    if ($check > 1) {
                        include('src/build/view/msgfmt-run.php');
                        return;
                    }
                }
            }
            system(sprintf('chmod 755 %s/ -R', BuildControl::SourceLang));
            echo 'OK';
        }
    }

}
