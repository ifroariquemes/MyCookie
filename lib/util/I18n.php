<?php

namespace lib\util;

require_once ('I18n/functions.php');

class I18n {

    /**
     * The instance for singleton
     * @var I18n 
     */
    private static $instance;

    /**
     * The singleton method
     * @return I18n
     */
    public static function singleton() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    function __construct() {
        /* @var $_MyCookie \lib\MyCookie */
        global $_MyCookie;
        $mc = $_MyCookie->getMyCookieConfiguration();
        $locale = ($mc->lang === 'user') ? \Locale::acceptFromHttp(filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE')) : $mc->lang;
        putenv("LANG=$locale");
        if (setlocale(LC_MESSAGES, $locale) === false) {
            if (setlocale(LC_MESSAGES, "$locale.utf8") === false) {
                throw new \Exception("Locale short code '$locale' not supported.");
            }
        }
        $this->BindDomains();
    }

    private function BindDomains() {
        $fHandle = opendir('src/config');
        while (($file = readdir($fHandle)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $fileInfo = explode('.', $file);
                bindtextdomain($fileInfo[0], 'src/lang');                
                bind_textdomain_codeset($fileInfo[0], 'UTF-8');
            }
        }
    }

}
