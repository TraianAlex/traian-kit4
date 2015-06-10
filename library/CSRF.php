<?php

class CSRF{
    
    public static function init(){
        
        $tok = new SynchronizerTokenPattern();
        define("T", bin2hex($tok->getToken(87).$tok->getToken(87)));//n
        define("T1", base64_encode(openssl_random_pseudo_bytes(64)));//v
    }
 /**
 * double token in url and input. if there is a name=v in input check
 * base64_encode(openssl_random_pseudo_bytes(64) not for url
 */
    public static function tokenize(){

        Sessions::set_session('n', T);
        Sessions::set_session('v', T1);
        Sessions::set_session('time', time());
        return '<input type="hidden" name="v" value="'.T1.'">';
    }

    public static function checkToken($post_v){

        $tok = (new Validate)->check(get_url()[2]);
        if(!Sessions::exist('n') || !Sessions::exist('v') || !$tok || !isset($post_v) ||
            $tok === false || empty($post_v) ||
            $tok !== Sessions::get('n') || $post_v !== Sessions::get('v') ||
            !Sessions::exist('time') || (time() - Sessions::get('time') >= 180)){
            Errors::handle_error2(null,'&#x2718; Try again.');
            exit;
        }
    }
/**
 * tokenize in url address only. for some links
 */
    public static function tokenizeUrl(){

        Sessions::set_session('token', T);
        Sessions::set_session('time', time());
    }

    public static function checkTokenUrl(){

        if(!Sessions::exist('token') || !Input::get('token') ||
            Input::exist('token', 'get')=== false || Input::get('token') !== Sessions::get('token') ||
            !Sessions::exist('time') || (time() - Sessions::get('time') >= 120)){
            Errors::handle_error2(null,'&#x2718; Try again.');
            exit;
        }
    }
/*
 * token only in input. for some forms submited only with ajax
 */
    public static function tokenizeInput(){

        Sessions::set_session('v1', T1);
        Sessions::set_session('time', time());
        return '<input type="hidden" name="v1" id="v1" value="'.T1.'">';
    }

    public static function checkTokenInput($post_v1){

        if(!Sessions::exist('v1') || !isset($post_v1) || empty($post_v1) ||
            $post_v1 !== Sessions::get('v1') ||
            !Sessions::exist('time') || (time() - Sessions::get('time') >= 180)){
            Errors::handle_error2(null,'&#x2718; Try again.');
            exit;
        }
    }
}