<?php

namespace controller\build;

class BuildFileController
{

    const RJSFile = 'components/r.js';
    const BuildJSFile = 'components/build-js.js';
    const BuildCSSFile = 'components/build-css.js';
    const BuildJSConfigFile = 'components/build-config.js';
    const BuildCSSConfigFile = 'components/build-config.css';
    const BuildMyCookieJSVariablesFile = 'components/mycookie.js.php';
    const SourceJS = 'src/assets/js';
    const SourceCSS = 'src/assets/css';
    const SourceViews = 'src/view';
    const SourceLang = 'src/lang';
    const SourceModulesConfig = 'src/config';

    public function createBuildJS($jsFiles)
    {
        unlink(self::BuildJSFile);
        $fp = fopen(self::BuildJSFile, 'w+');
        fwrite($fp, "({\n");
        fwrite($fp, "   baseUrl: \"../\",\n");
        fwrite($fp, "   paths: {\n");
        foreach ($jsFiles as $key => $value) {
            fwrite($fp, sprintf("       %s: \"%s\",\n", $key, $value));
        }
        fwrite($fp, "   },\n");
        fwrite($fp, "   name: \"components/build-config\",\n");
        fwrite($fp, "   out: \"bundle.js\"\n");
        fwrite($fp, "})");
        fclose($fp);
        $this->createBuildJSConfig($jsFiles);
    }

    private function createBuildJSConfig($jsFiles)
    {
        unlink(self::BuildJSConfigFile);
        $fp = fopen(self::BuildJSConfigFile, 'w+');
        fwrite($fp, 'require([');
        foreach ($jsFiles as $key => $value) {
            fwrite($fp, "'$key',");
        }
        fwrite($fp, '], function() { });');
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

    public function generatePortableObjects()
    {
        $fHandle = opendir(self::SourceViews);
        array_map('unlink', glob(sprintf('%s/_po/*', self::SourceLang)));
        while (($file = readdir($fHandle)) !== false) {
            if ($file !== '.' && $file !== '..') {
                if (count(scandir(sprintf('%s/%s', self::SourceViews, $file))) > 2) {
                    if (!BuildCommandController::generatePortableObject($file)) {
                        exit;
                    }
                    $fileName = sprintf('%s/_po/%s.po', self::SourceLang, $file);
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

    public function generateMachineObjects()
    {
        array_map('unlink', glob(sprintf('%s/*.mo', self::SourceLang)));
        foreach ($this->SeekFiles(self::SourceLang, 'po') as $po) {
            if (strpos($po, '_po') === false) {
                if (!BuildCommandController::generateMachineObject($po)) {
                    exit;
                }
            }
        }
        echo 'OK';
    }

    public function buildMyCookieJSVariables()
    {
        unlink(self::BuildMyCookieJSVariablesFile);
        $fp = fopen(self::BuildMyCookieJSVariablesFile, 'w+');
        foreach ($this->SeekFiles(self::SourceModulesConfig) as $module) {
            $moduleInfo = explode('/', $module);
            $ns.= sprintf("'%s',", explode('.', end($moduleInfo))[0]);
        }
        $ns = substr($ns, 0, -1);
        $content = <<<CONTENT
<?php global \$_MyCookie; ?>
<script type="text/javascript">
    MYCOOKIEJS_ACTION = '<?php echo \$_MyCookie->getAction(); ?>';
    MYCOOKIEJS_MODULE = '<?php echo \$_MyCookie->getModule(); ?>';
    MYCOOKIEJS_AUXILIARMODULE = '<?php echo \$_MyCookie->getAuxiliarModule(); ?>';
    MYCOOKIEJS_NAMESPACE = '<?php echo str_replace('\\\\', '\\\\\\\\', \$_MyCookie->getNamespace()); ?>';
    MYCOOKIEJS_SITE = '<?php echo \$_MyCookie->getSite(); ?>';
    MYCOOKIEJS_ALERT = '<?php _e('System message', 'administrator') ?>';
    MYCOOKIEJS_CONFIRMATION = '<?php _e('Confirmation', 'administrator') ?>';
    MYCOOKIEJS_YES = '<?php _e('Yes', 'administrator') ?>';
    MYCOOKIEJS_NO = '<?php _e('No', 'administrator') ?>';    
</script>
CONTENT;
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
