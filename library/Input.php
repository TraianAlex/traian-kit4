<?php

class Input{
    
    public static function exist($name , $type = 'post') {
        
        switch ($type){
            case 'post':
                return (isset($_POST[$name]) && !empty($_POST[$name])) ? true : false;
            break;
            case 'get':
                return (isset($_GET[$name]) && !empty($_GET[$name])) ? true : false;
            break;
            default:
                return false;
            break;
        }
    }
    
    public static function get($item) {
        
//        if(isset($_POST[$item])){
//            return $_POST[$item];
//        }else if(isset($_GET[$item])){
//            return $_GET[$item];
//        }
//        return '';
        return isset($_POST[$item]) ? $_POST[$item] : (isset($_GET[$item]) ? $_GET[$item] : '');
    }
}
