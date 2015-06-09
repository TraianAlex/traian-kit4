<?php

class Sessions{

    public function __construct() {}

    public static function exist($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }
    
    public static function set_session($name, $value) {
        return $_SESSION[$name] = $value;
    }
    
    public static function get($name) {
        return $_SESSION[$name];
    }
    
    public static function delete($names) {
        if (is_array($names) && !empty($names)){
            foreach($names as $name){
                if(self::exist($name)){
                    unset($_SESSION[$name]);
                }
            }
        } else {
            if(self::exist($names)){
                unset($_SESSION[$names]);
            }
        }
    }

    public static function init() {
        
        self::setHeader();
        self::setSession();
        self::setCanary();
        self::setUserAgent();
        self::set_session('timelimit', 600);
        self::set_session('start', time());
    }

    private static function setHeader() {

        header("Expires: Tu, 31 Dec 2015 05:00:00 GMT");
        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
        header("Content-Type: text/html");
        header("Accept-Encoding: gzip, deflate");
        //header("Pragma: public");//no-cache
        //header("Expires: 0");
        //header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        //header("Cache-Control: post-check=0, pre-check=0",false);
        header("Cache-Control: public");//no-cache//private or private_no_expire
        //header("Content-Description: File Transfer");
        //header("Content-Type: application/vnd.ms-excel");
        //header('Content-Disposition: attachment; filename="fileToExport.xls"');
    }

    private static function setSession() {

        session_regenerate_id();
        session_start();
        if (!self::exist('generated') || self::get('generated') < (time() - 300)) {
            session_regenerate_id();
            self::set_session('generated', time());
            URL::to(SITE_ROOT.'/');
        }
        ob_start();
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 0); //1 ?
        //ini_set('session.cookie_lifetime', '600');
        //ini_set('session.save_path', '/home/user/myaccount/sessions');
        ini_set('session.cookie_httponly', true);
        //ini_set('session.cookie_secure', true);//if use you can't login

        //ini_set('session.entropy_file', '/dev/urandom');
        //ini_set('session.entropy_length', '32');
        //ini_set('session.hash_function', 'sha256');
        //ini_set('session.hash_bits_per_character', '6');

        //session_cache_limiter("must-revalidate");
        //session_cache_limiter('public');////no-cache//private or private_no_expire
        //session_cache_limiter('private, must-revalidate');
        //session_cache_expire(60); // in minutes
        ini_set('session.use_strict_mode', 1);
    }
    
    private static function setCanary() {

        if (empty($_SESSION['canary'])) {
            self::set_session('canary', sha1($_SERVER['REMOTE_ADDR']));
            session_regenerate_id(false);
        }
    }

    private static function setUserAgent() {

        if (empty($_SESSION['userAgent'])) {
            self::set_session('userAgent', sha1($_SERVER['HTTP_USER_AGENT']));
            session_regenerate_id(false);
        }
    }
/**
 * index
 * @return null
*/
    public static function setUser() {

        if (!self::exist('user'))
            self::set_session('user', null);
        return self::get('user');
    }
 /**
 * in header, footer
 */
    public static function chechAuth() {

        if(!self::exist('user'))
            echo "onClick=\"auth()\"";     
    }
    
    public function attempt() {

        if(self::exist('att') && self::get('att') > 4){
            return false;
        }
        if(!self::exist('att')){
           self::set_session('att', 0);
           return true;
        }
    }
    
    public function checkUser() {

        if(!self::exist('id') || !self::exist('user') || !self::get('user')
             || self::get('id') != sha1(K.sha1(session_id().K)) ||
            (self::get('userAgent') != sha1($_SERVER['HTTP_USER_AGENT'])) ||
            (self::get('canary') != sha1($_SERVER['REMOTE_ADDR']))){
            self::delete(array('user_id', 'user'));
                return false;
        }elseif (time() > self::get('start') + self::get('timelimit')) {
            $this->destroySession();
            return false;
        }else{
            self::set_session('start', time());
            return true;
        }
    }
    
    public function checkAdmin() {

       if(!self::exist('id') || !self::exist('user') || !self::get('user') ||
               self::get('id') != sha1(K1.sha1(session_id().K1)) ||
               (self::get('userAgent') != sha1($_SERVER['HTTP_USER_AGENT'])) ||
               (self::get('canary') != sha1($_SERVER['REMOTE_ADDR']))){
            self::delete(array('id', 'userAgent', 'canary', 'user'));
            return false;
        }elseif (time() > self::get('start') + self::get('timelimit')) {
            $this->destroySession();
            return false;
        }else{
            self::set_session('start', time());
            return true;
        }
    }

    public function set_token(){

        $t = sha1(strval(date('W')) . 'YourSpecialValueHere');
        $this->set_session('t', $t);
        output_add_rewrite_var('t', $t);
    }

    public function check_token(){

        if (!isset($_REQUEST['t']) || $_REQUEST['t'] != $this->get('t'))
            Errors::handle_error2(null,'You must login to see this page.');
    }
    
    private function destroySession(){

        if (isset($_COOKIE[session_name()]))
            setcookie(session_name(), '', time()-86400, '/');
        $_SESSION = array();
        session_unset(); //session_destroy();
    } 

    public function logout() {
        
        self::delete(array('user_id', 'user', 'pagesize'));
        if(self::exist('gif') && file_exists(self::get('gif'))) unlink (self::get('gif'));
        if (isset($_COOKIE[session_name()]))
            setcookie(session_name(), '', time()-86400, '/');
        session_destroy();
    }
    
    public function delete_session(){
        self::delete(array('reg', 'token', 'n', 'v', 'time'));
    }

    public function delete_session_messages(){
        self::delete(array('submit', 'success_msg', 'error_msg', 'track'));
        ob_flush();
    }
  
    public function __destruct() {
        session_commit();
    }

}