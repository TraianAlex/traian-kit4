<?php

class Registry{

    private static $instance;

    private function __construct() { }
    private function __clone(){}

    public static function getInstance($default = null){
        if (isset(self::$instance)){
            return self::$instance;
        }
        return $default;
    }

    public static function setClass(){

       $class = ucfirst(self::getClass()."_C");
       if(file_exists(APP_PATH . '/controller/'. $class . '.php'))
            return self::$instance = new $class($_POST);
       unset($class);
   }
   
   private static function getClass() {
        
        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : "";
        $class = isset(get_url()[0]) ? get_url()[0] : $config_page['default_class'];
        if(!in_array($class, $config_class['allowed']))
            $class = $config_page['default_class'];
        return Validate::check($class);
    }
}