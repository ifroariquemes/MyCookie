<?php

namespace lib\util;

class Server
{

    const OS_MAC = 'MAC';
    const OS_WINDOWS = 'WINDOWS';
    const OS_UNIX = 'UNIX';    
    
    private $os;
    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function getOS()
    {
        if (empty($this->os)) {
            $osServer = $_SERVER['SERVER_SIGNATURE'];
            if (strpos($osServer, 'Unix') !== false) {
                $this->os = 'UNIX';
            } else if (strpos($osServer, 'Win32') !== false) {
                $this->os = 'WINDOWS';
            } else {
                $this->os = 'MAC';
            }
        }
        return $this->os;
    }

}
