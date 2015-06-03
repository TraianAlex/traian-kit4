<?php

class Core{

        private static $_paths = array("model", "view", "controller", "application", "library",
             "");

        public static function initialize(){

            if (!defined("APP_PATH")){
                throw new Exception("APP_PATH not defined");
            }
            // fix extra backslashes in $_POST/$_GET
            if (get_magic_quotes_gpc()){
               $globals = array("_POST","_GET","_COOKIE","_REQUEST","_SESSION");
               foreach ($globals as $global){
                    if (isset($GLOBALS[$global])){
                        $GLOBALS[$global] = self::_clean($GLOBALS[$global]);
                    }
                }
            }
            // start autoloading
            $paths = array_map(__CLASS__."::_load", self::$_paths);
            $paths[] = get_include_path();
            set_include_path(join(PATH_SEPARATOR, $paths));
            spl_autoload_register(__CLASS__."::_autoload");
            set_time_limit(0);
            ini_set('date.timezone', 'America/Toronto');
        }

        protected static function _clean($array){
            if (is_array($array)){
                return array_map(__CLASS__."::_clean", $array);
            }
            return stripslashes($array);
        }

        protected static function _autoload($class){

            $paths = explode(PATH_SEPARATOR, get_include_path());
            $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
            $file = $class.".php";
            foreach ($paths as $path){
                $combined = $path.DIRECTORY_SEPARATOR.$file;
                if (file_exists($combined)){
                    include_once($combined);
                    return;
                }
            }
            throw new Exception("{$class} not found");
        }

         private static function _load($item) {
            return APP_PATH.DIRECTORY_SEPARATOR.$item;
	}

}
