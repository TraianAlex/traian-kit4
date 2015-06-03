<?php

class Admins extends DB_Manager{
    
    protected $data = [
        "id_admin" => "",
        "user" => "",
        "newadmin" => "",
        "pwd" => "",
        "repwd" => "",
        "firstname" => "",
        "lastname" => "",
        "city" => "",
        "email_adm" => "",
        "p_email" => ""];//profile email
    
    public function login_admin() {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'admins';
        $arr = self::$instance->select('id_admin, username, password, salt')->where('username', $this->data['user'])
                            ->where('uid', '7')->get_obj();
        if(self::$instance->get_result_count() == 1){
            foreach($arr as $log){
                $res = Hash::validate_password($this->data['pwd'], PBKDF2_HASH_ALGORITHM.':'.PBKDF2_ITERATIONS.':'.$log->salt.':'.$log->password);
                return $res ? $arr : false ;
            }
        }
        return false;
    }
    
    public function login_sup() {

        $pwd = hash('sha256', $this->data['user'] . $this->data['pwd'] . KS);
        return ($this->data['user'] === SUP && $pwd === PWD) ? Sessions::set_session('user', 'sup') : false;
    }
    
    public function register_admin() {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'admins';
        $pak = Hash::create_hash($this->data['pwd']);
        $pwd = explode(':', $pak);
        $result = self::$instance->values(array('username' => $this->data['newadmin'],
                                           'password' => $pwd[3],
                                              'salt' => $pwd[2],
                                              'email' => $this->data['email_adm'],
                                               'uid' => '7',
                                                'ip' => htmlspecialchars($_SERVER['REMOTE_ADDR']),
                                            'created' => date("Y/m/d H:i:s"),
                                            'updated' => date("Y/m/d H:i:s")))->insert();
        if($result){
            return true;
        }
        $result->closeCursor();
        return false;
    }  
/*
 * confirm if a username is taken
 */
    public static function get_admins($p) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'admins';
        $res = self::$instance->select('username')->where('username', $p)->where('uid', '7')->get();
        return $res ?: false;
    }
/*
 * used to validate email at registration //and data skipping the curent email @fixme
 * confirmation that a email is registered for a pass recover
 *  * check email and skip by curent user when try to change the email
 */
    public static function get_email_adm($p) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'admins';
        $res = self::$instance->select('*')->where('email', $p)->where('uid', '7')->getAll();
//        if(Sessions::exist('temp_email')){
//            if($p != Sessions::get('temp_email') && $res){
//                return true;
//            }
//        }else {
//            return $res;
//        }
        return Sessions::exist('temp_email') ? ($p != Sessions::get('temp_email') && $res) : TRUE ? $res : FALSE;
    }
    
    public function get_users() {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $res = self::$instance->select('username, email, uid, ip, created, updated')->getAll();
        return $res ?: false;
    }
    
    public function get_all_admins() {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'admins';
        $res = self::$instance->select('username, email, uid, ip, created, updated')->getAll();
        return $res ?: false;
    }
}