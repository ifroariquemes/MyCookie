<?php

namespace controller\build;

use lib\util\Server;

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

    public function createBuildJS()
    {
        global $_MyCookie;
        if ($this->checkPassword()) {
            $config = $_MyCookie->getMyCookieConfiguration();
            $this->addJS('mycookie', 'components/mycookie');
            foreach ($config->build->custom_js as $key => $value) {
                $this->addJS($key, $value);
            }
            $files = $this->bfCtrl->seekFiles(BuildFileController::SourceJS, 'js');
            foreach ($files as $js) {
                $filePath = explode('.', $js);
                $fileName = explode('/', $filePath[0]);
                $fileId = sprintf('%s_%s', array_pop($fileName), array_pop($fileName));
                $this->addJS($fileId, $filePath[0]);
            }
            $this->bfCtrl->createBuildJS($this->jsFiles);
        } else {
            echo 'The password does\'t match';
        }
    }

    public function createBuildCSS()
    {
        global $_MyCookie;
        if ($this->CheckPassword()) {
            $config = $_MyCookie->getMyCookieConfiguration();
            foreach ($config->build->custom_css as $key => $value) {
                $this->addCSS($value);
            }
            $files = $this->bfCtrl->seekFiles(BuildFileController::SourceCSS, 'css');
            foreach ($files as $css) {
                $this->addCSS($css);
            }
            $this->bfCtrl->createBuildCSS($this->cssFiles);
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

    public function buildMyCookieJSVariables()
    {
        if ($this->CheckPassword()) {
            $this->bfCtrl->buildMyCookieJSVariables();
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
        global $_MyCookie;
        $myCookieConfiguration = $_MyCookie->getMyCookieConfiguration();
        return filter_input(INPUT_POST, 'password') ===
                md5($myCookieConfiguration->build->password);
    }

    public function checkPasswordRet()
    {
        var_export($this->CheckPassword());
    }

    /**
     * If not generating, please check permissions
     */
    public function generatePortableObjects()
    {
        if ($this->CheckPassword()) {
            $this->bfCtrl->generatePortableObjects();
        }
    }

    /**
     * If not generating, please check permissions
     */
    public function generateMachineObjects()
    {
        if ($this->CheckPassword()) {
            $this->bfCtrl->generateMachineObjects();
        }
    }

}
