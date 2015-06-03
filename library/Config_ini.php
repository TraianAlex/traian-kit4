<?php

final class Config_ini {

    /** @var array config data */
    private static $data = null;
    /**
     * @return array
     * @throws Exception
     */
    public static function getConfig($section = null) {
        if ($section === null) {
            return self::getData();
        }
        $data = self::getData();
        if (!array_key_exists($section, $data)) {
            throw new Exception('Unknown config section: ' . $section);
        }
        return $data[$section];
    }

    /**
     * @return array
     */
    private static function getData() {
        if (self::$data !== null) {
            return self::$data;
        }
        file_exists('../application/config.ini') ?
            self::$data = parse_ini_file('../application/config.ini', true) :
            self::$data = parse_ini_file('../../application/config.ini', true);
        return self::$data;
    }

    public static function get($path = null){
        
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            foreach ($path as $bit){
                if(isset($config[$bit])){
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}

//use for last method
/*
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'aaaa',
        'db' => 'remb4372_traian'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);
var_dump(Config_ini::get('mysql/host'));*/