<?php

namespace controller\build;

class BuildFileController
{
    const RJSFile = 'components/r.js';
    const BuildJSFile = 'components/build-js.js';
    const BuildCSSFile = 'components/build-css.js';
    const BuildJSConfigFile = 'components/build-config.js';
    const BuildCSSConfigFile = 'components/build-config.css';
    const BuildMyCookieJSVariablesFile = 'components/mycookie.js';
    const I18N_CONFIG_FILE = 'components/i18next.config.js';
    const I18N_DEFAULT_PATH = 'src/lang/dev';
    const SourceJS = 'src/assets/js';
    const SourceCSS = 'src/assets/css';
    const SourceViews = 'src/view';
    const SourceLang = 'src/lang';
    const SourceModulesConfig = 'src/config';

    public function createBuildJS($jsFiles)
    {
        global $_MyCookie;
        unlink(self::BuildJSFile);
        $fp = fopen(self::BuildJSFile, 'w+');
        $content = $_MyCookie->loadView('build', 'build-js', $jsFiles, true);
        fwrite($fp, $content);
        fclose($fp);
        $this->createBuildJSConfig($jsFiles);
    }

    private function createBuildJSConfig($jsFiles)
    {
        global $_MyCookie;
        unlink(self::BuildJSConfigFile);
        $fp = fopen(self::BuildJSConfigFile, 'w+');
        $c = $_MyCookie->loadView('build', 'build-config', $jsFiles, true);
        fwrite($fp, $c);
        fclose($fp);
    }

    public function createBuildCSS($cssFiles)
    {
        unlink(self::BuildCSSFile);
        $fp = fopen(self::BuildCSSFile, 'w+');
        fwrite($fp, "({\n");
        fwrite($fp, "   cssIn: \"build-config.css\",\n");
        fwrite($fp, "   out: \"bundle.css\",\n");
        fwrite($fp, "   optimizeCss: \"default\"\n");
        fwrite($fp, "})");
        fclose($fp);
        $this->createBuildCSSConfig($cssFiles);
    }

    private function createBuildCSSConfig($cssFiles)
    {
        unlink(self::BuildCSSConfigFile);
        $fp = fopen(self::BuildCSSConfigFile, 'w+');
        foreach ($cssFiles as $css) {
            fwrite($fp, "\n@import url('../$css');");
        }
        fclose($fp);
    }

    public function cleanCache()
    {
        unlink(self::BuildCSSConfigFile);
        unlink(self::BuildCSSFile);
        unlink(self::BuildJSConfigFile);
        unlink(self::BuildJSFile);
        array_map('unlink', glob('cache/*'));
    }

    public function buildMyCookieJSVariables()
    {
        global $_BaseURL;
        $fp = file(self::BuildMyCookieJSVariablesFile);
        array_pop($fp);
        array_push($fp, "MYCOOKIEJS_BASEURL = '$_BaseURL';");
        unlink(self::BuildMyCookieJSVariablesFile);
        file_put_contents(self::BuildMyCookieJSVariablesFile, $fp);
    }

    public function createI18NConfig()
    {
        global $_MyCookie;
        global $_BaseURL;
        global $_Config;
        $ns = '';
        foreach ($this->seekFiles(self::I18N_DEFAULT_PATH, 'json') as $file) {
            $pathInfo = explode('/', $file);
            $fileInfo = explode('.', end($pathInfo));
            $ns .= "'{$fileInfo[0]}',";
        }
        $ns = substr($ns, 0, -1);
        unlink(self::I18N_CONFIG_FILE);
        $fp = fopen(self::I18N_CONFIG_FILE, 'w+');
        $content = $_MyCookie->loadView('build', 'i18n.config'
                , array(
            'lang' => $_Config->lang,
            'ns' => $ns,
            'site' => $_BaseURL), true);
        fwrite($fp, $content);
        fclose($fp);
    }

    public function seekFiles($path, $extension = '*')
    {
        if (is_file($path)) {
            return $path;
        }
        if (is_dir($path)) {
            $files = array();
            $fHandle = opendir($path);
            while (false !== ($file = readdir($fHandle))) {
                if ($file !== '.' && $file !== '..') {
                    $fileFound = $this->seekFiles("$path/$file");
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
}