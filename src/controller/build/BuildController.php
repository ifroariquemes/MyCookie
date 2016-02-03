<?php

namespace controller\build;

class BuildController
{
    private $bfCtrl;
    private $bmCtrl;
    private $cssFiles = array();
    private $jsFiles = array();

    public function __construct()
    {
        $this->bfCtrl = new BuildFileController();
        $this->bmCtrl = new BuildCommandController();
    }

    public function getCSSFiles()
    {
        return $this->cssFiles;
    }

    public function getJSFiles()
    {
        return $this->jsFiles;
    }

    public function build()
    {
        global $_MyCookie;
        global $_Async;
        $_Async = true;
        $_MyCookie->loadView('build', 'build');
    }

    public function addJS($fileId, $filePath)
    {
        if (substr($filePath, -3, 3) == '.js') {
            $this->jsFiles[$fileId] = substr($filePath, 0, -3);
        } else {
            $this->jsFiles[$fileId] = $filePath;
        }
    }

    public function addCSS($filePath)
    {
        if (substr($filePath, -3, 3) == '.css') {
            array_push($this->cssFiles, substr($filePath, 0, -3));
        } else {
            array_push($this->cssFiles, $filePath);
        }
    }

    public function createBuildJS($internal = false)
    {
        global $_Config;
        if ($this->checkPassword() || $internal) {
            $this->bfCtrl->createI18NConfig();
            $this->bfCtrl->buildMyCookieJSVariables();
            $this->addJS('jquery', 'components/jquery');
            $this->addJS('mycookie', 'components/mycookie');
            $this->addJS('i18next', 'components/i18next');
            $this->addJS('i18next_config', 'components/i18next.config');
            foreach ($_Config->build->custom_js as $key => $value) {
                $this->addJS($key, $value);
            }
            $files = $this->bfCtrl->seekFiles(BuildFileController::SourceJS, 'js');
            foreach ($files as $js) {
                $filePath = explode('.', $js);
                $fileName = explode('/', $filePath[0]);
                $fileId = sprintf('%s_%s', array_pop($fileName), array_pop($fileName));
                $this->addJS($fileId, $filePath[0]);
            }
            if (!$internal) {
                $this->bfCtrl->createBuildJS($this->jsFiles);
            }
        } else {
            echo 'The password does\'t match';
        }
    }

    public function createBuildCSS($internal = false)
    {
        global $_Config;
        if ($this->CheckPassword() || $internal) {
            foreach ($_Config->build->custom_css as $key => $value) {
                $this->addCSS($value);
            }
            $files = $this->bfCtrl->seekFiles(BuildFileController::SourceCSS, 'css');
            foreach ($files as $css) {
                $this->addCSS($css);
            }
            if (!$internal) {
                $this->bfCtrl->createBuildCSS($this->cssFiles);
            }
        }
    }

    public function buildJS()
    {
        if ($this->CheckPassword()) {
            $this->bmCtrl->createJSBundle();
        } else {
            echo 'The password does\'t match';
        }
    }

    public function buildCSS()
    {
        if ($this->CheckPassword()) {
            $this->bmCtrl->createCSSBundle();
        } else {
            echo 'The password does\'t match';
        }
    }

    public function updateSchema()
    {
        if ($this->CheckPassword()) {
            $this->bmCtrl->updateDatabase();
        } else {
            echo _e("The password doesn't match", 'build');
        }
    }

    public function recreateSchema()
    {
        if ($this->CheckPassword()) {
            $this->bmCtrl->recreateDatabase();
        } else {
            echo 'The password does\'t match';
        }
    }

    private function checkPassword()
    {
        global $_Config;
        return filter_input(INPUT_POST, 'password') ===
                md5($_Config->build->password);
    }

    public function checkPasswordRet()
    {
        var_export($this->CheckPassword());
    }

    public function teste()
    {
        $this->bfCtrl->createI18NConfig();
    }
}