<?php

class Users extends DB_Manager{
    
    protected $data = [
        "id_user" => "",
        "user" => "",
        "newuser" => "",
        "pwd" => "",
        "repwd" => "",
        "firstname" => "",
        "lastname" => "",
        "city" => "",
        "email" => "",
        "p_email" => ""];//profile email
     
    public function login_user() {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $arr = self::$instance->select('id_user, username, password, salt')->where('username', $this->data['user'])
                 ->where('uid', '3')->get_obj();
        if(self::$instance->get_result_count() == 1){
            foreach($arr as $log){
                $res = Hash::validate_password($this->data['pwd'], PBKDF2_HASH_ALGORITHM.':'.PBKDF2_ITERATIONS.':'.$log->salt.':'.$log->password);
                return $res ? $arr : false ;
            }
        }
        return false;
    }
    
    public function register_user() {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $pak = Hash::create_hash($this->data['pwd']);
        $pwd = explode(':', $pak);
        $result = self::$instance->values(['username' => $this->data['newuser'],
                                           'password' => $pwd[3],
                                              'salt' => $pwd[2],
                                              'email' => $this->data['email'],
                                               'uid' => '3',
                                                'ip' => htmlspecialchars($_SERVER['REMOTE_ADDR']),
                                            'created' => date("Y/m/d H:i:s"),
                                            'updated' => date("Y/m/d H:i:s")])->insert();
        if($result) return true;
        $result->closeCursor();
        return false;
    }
    
    public function get_uid($p) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $res = self::$instance->select('uid')->where('username', $p)->get();
        return ($res['uid'] === '3') ? true : false;
    }
/*
 * confirm if a username is taken
 * profile
 */
    public static function get_users($p) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $res = self::$instance->select('username, password, email')->where('username', $p)->where('uid', '3')->get_obj();
        return $res ?: false;
    }
/*
 * used to validate email at registration //and data skipping the curent email @fixme
 * confirmation that a email is registered for a pass recover
 * check email and skip by curent user when try to change the email
 */
    public static function get_email($p) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $res = self::$instance->select('email')->where('email', $p)->where('uid', '3')->get();
//        if(Sessions::exist('temp_email')){
//            if($p != Sessions::get('temp_email') && $res){
//                return true;
//            }
//        }else {
//            return $res;
//        }
        return Sessions::exist('temp_email') ? ($p != Sessions::get('temp_email') && $res) : TRUE ? $res : FALSE;
    }  
/**
 * after the user submit the form with his email he get the new temp hash password
 * send email with the new password
 * f_email need to confirm that the email exist in db
 * update pwd with a hash string
 * @param string $p
 * @return string
 */
    public function send_email_reset(){

        $this->data['email'] = filter_input(INPUT_POST, 'f_email', FILTER_SANITIZE_EMAIL);
        $p = substr ( md5(uniqid(rand(), true)), 3, 10);
        $content ="Dear user,
    Your password to log in has been temporarily changed to: $p
    Please log into ".ADDRESS.SITE_ROOT." using this password.";
        $subject = "Email verification";
        $from = "FROM: www.traian3.embassy-pub.ro";
        if($this->resetPass($p, $this->data['email'])){
            mail($this->data['email'], $subject, $content, $from);
            throw new Exception('&#x2714; Instructions regarding resetting your password have been sent to '.$this->data['email']);
        }
        return false;
    }
/**
 * update pass with a string hash so the user can't aut
 * @param int $id
 * @param string $email
 */
    private function resetPass($p, $email){

        $row = $this->getUserByEmail($email);
        $pak = Hash::create_hash($p);
        $pwd = explode(':', $pak);
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $res = self::$instance->set(['password' => $pwd[3], 'salt' => $pwd[2]])
                ->where('email', $email)->where('uid', '3')->update();
        return $res ? true : false;
    }
/**
 * get username by email to set up the new temp pass
 * @param string $uid
 */
    private function getUserByEmail($email) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $result = self::$instance->select('username')->where('email', strip_tags($email))->where('uid', '3')->get();
        return $result ?: false;
    } 
/**
 * p_email = profile email has another validation than email
 * the user can change the email and don't massage him that
 * his email already exist
 * edit profile
 */
    public function update(){

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $res = self::$instance->set(['email' => $this->data['p_email'], 'updated'=> date("Y/m/d H:i:s")])
                ->where('username', $_SESSION['user'])->where('uid', '3')->update();
        return $res ? true : false;
    }
/**
 * change password
 * @param string $p
 * @return boolean
 */
    public function change_password(){

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $pak = Hash::create_hash($this->data['pwd']);
        $pwd = explode(':', $pak);
        $res = self::$instance->set(['password' => $pwd[3], 'salt' => $pwd[2], 'updated'=> date("Y/m/d H:i:s")])
                ->where('username', $_SESSION['user'])->where('uid', '3')->update();
        return $res ? true : false;
    }
/*
 * validation pass for changing
 */
    public static function checkHashConfirmation($user) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $statement = self::$instance->select('password, salt')->where('username', $user)->where('uid', '3')->get();
        return $statement ?: false;
    }
/*
 * create a image email
 */  
    public static function get_user_data($id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'users';
        $result = self::$instance->select('email')->where('id_user', $id)->where('uid', '3')->get();
        return $result ?: false;
    }
    
    public function check_oath($id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users';
        $arr = self::$instance->select('*')->where('fld_google_id', $id)->get();
        return $arr ? true : false;
    }
    
    public function insert_oath($name, $email, $id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users';
        $time = time();
        $result = self::$instance->values(['fld_user_name' => $name,
                                           'fld_user_email' => $email,
                                            'fld_google_id' => $id,
                                             'fld_user_doj' => $time])->insert();
        if($result) return true;
        $result->closeCursor();
        return false;
    }
    
    public function update_oath($name, $email, $id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users';
        $res = self::$instance->set(['fld_user_name' => $name, 'fld_user_email' => $email])
                ->where('fld_google_id', $id)->update();
        return $res ? true : false;
    }
    
    public function get_users_oath($id) {

        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users';
        $res = self::$instance->select('fld_user_name, fld_user_email')->where('fld_google_id', $id)->get_obj();
        return $res ?: false;
    }
    
    public function check_fb($id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users_fb';
        $arr = self::$instance->select('*')->where('fld_facebook_id', $id)->get();
        return $arr ? true : false;
    }
    
    public function insert_fb($name, $email, $id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users_fb';
        $time = time();
        $result = self::$instance->values(['fld_user_name' => $name,
                                           'fld_user_email' => $email,
                                          'fld_facebook_id' => $id,
                                             'fld_user_doj' => $time])->insert();
        if($result) return true;
        $result->closeCursor();
        return false;
    }
    
    public function update_fb($name, $email, $id) {
        
        self::$instance = DB_Manager::get_instance($_POST)->database();
        self::$instance->table = 'tbl_users_fb';
        $res = self::$instance->set(['fld_user_name' => $name, 'fld_user_email' => $email])
                ->where('fld_facebook_id', $id)->update();
        return $res ? true : false;
    }
}