<?php

namespace lib\util;

require_once ('I18n/functions.php');

class I18n
{

    const LC_MESSAGES = 5;        

    private static $langWindows = array('en_US' => 'us', 'pt_BR' => 'ptb');
    
    /**
     * The instance for singleton
     * @var I18n 
     */
    private static $instance;

    /**
     * The singleton method
     * @return I18n
     */
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    function __construct()
    {
        $this->setLocale();
        $this->bindDomains();
    }

    function setLocale()
    {
        global $_MyCookie;
        global $_Server;
        $lang = $_MyCookie->getMyCookieConfiguration()->lang;
        $locale = ($lang === 'user') ? \Locale::acceptFromHttp(filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE')) : $lang;
        putenv("LANG=$locale");
        if ($_Server->getOS() == Server::OS_WINDOWS) {
            if (setlocale(I18n::LC_MESSAGES, self::$langWindows[$locale]) === false) {
                throw new \Exception("Locale short code '$locale' not supported.");
            }
        } else if (setlocale(I18n::LC_MESSAGES, $locale) === false) {
            if (setlocale(I18n::LC_MESSAGES, "$locale.utf8") === false) {
                throw new \Exception("Locale short code '$locale' not supported.");
            }
        }
    }

    private function bindDomains()
    {
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
