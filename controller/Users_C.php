<?php

final class Users_C extends Controller{
    
    protected function main(){
        $this->model('Users');
    }
                
    public function index() {
        parent::view('header');
        parent::view('users/form_login');
    }

    public function login_area() {    
        $this->view('header');
        $this->view('users/login_area');
    }
    
    public function nologin_area() {   
        $this->view('header');
        $this->view('users/home');
    }

    public function log_out() {   
        $this->session->logout();
        URL::to();
    }

/*---------------------------------------------------------------------------------*/
    
    public function login() {
        
        if ($this->input->exist('submit') && $this->input->get('submit') == 'Sign In') {
            $this->valid->validation($_POST);
            if($this->session->attempt() === false)
                URL::to("users/forgot_password");
            $row = call_user_func([new Users($_POST), 'login_user']);
            if(!$row){
                $this->session->delete_session();
                $this->session->set_session('att', $this->session->get('att') + 1);
                Errors::handle_error2(null,'The username or password does not match!');
            }
            $this->auth_user($row);
            URL::to("users/login_area");
        }
    }

    private function auth_user($row){

        foreach($row as $row):
            $this->session->set_session('user', $row->username);
            $this->session->set_session('user_id', (int)$row->id_user);
            $this->session->set_session('submit', "Welcome");
            $this->session->set_session('id', sha1(K . sha1(session_id(). K)));
        endforeach;
    }
    
    public static function check_user() {
        
        $data = call_user_func([new Users($_POST), 'get_uid'], Sessions::get('user'));
        return ($data == false) ? false : true;
    }

/*-----------------------------------------------------------------------------------*/

    public function forgot_password() {

        $this->view('header');
        $this->view('users/form_forgot_pass');
    }

    public function send_pass() {
        
        if ($this->input->exist('send_id') && $this->input->get('send_id') == 'Send link') {
            try{
                $this->valid->validation($_POST);
                $send = $this->Users->send_email_reset();
                if($send){
                    $this->session->delete_session();
                    throw new Exception();
                }else{
                    throw new Exception('&#x2718; Request failed');
                }
            }catch(Exception $e){
                Errors::handle_error2($e->getMessage(), null);
                exit;
            }
        }
    }

/*---------------------------------------------------------------------------------------*/
    
    public function register() {
        
        if ($this->input->exist('add_new_user') && $this->input->get('add_new_user') == 'Register'){
            try{
                $this->session->set_session('reg', $_POST);
                $this->valid->validation($_POST);
                //Captcha::responseCaptcha2();
                $reg = $this->Users->register_user();
                if($reg){
                    $this->session->delete_session();
                    throw new Exception('&#x2714; Registered!');
                }else{
                     throw new Exception('&#x2718; Register failed');
                    }
            }catch(Exception $e){
                 Errors::handle_error2($e->getMessage(), null);
                 URL::to("users/login_area");
            }
        }
        $this->view('header');
        $this->view('users/newuser_form');
    }

/*-----------------------------------------------------------------------------------*/
    
    public function profile() {

        //$vb = setVerb($view);
        //$pic = self::set_foto_profile($row['profile_pic_id']);
        //$arrData = [$this->model('Users', 'get_users', $_SESSION['user'])];
        $arrData = call_user_func([new Users($_POST), 'get_users'], $_SESSION['user']);
        $this->view('header');
        if($arrData)
            $this->view('users/links_profile', $arrData);
        $arrData2 = call_user_func([new Users($_POST), 'get_users_oath'], $_SESSION['user_id']);
        if($arrData2)
            $this->view('users/links_profile_oath', $arrData2);
    }
    
    public function personal_data() {

        $user = $this->Users->get_users($_SESSION['user']);
        //$pic = self::set_foto_profile($user['profile_pic_id']);
        //$delete = $this->setDeleteLink($user['profile_pic_id']);
        $this->view('header');
        $this->view('users/personal_data_form', $user);
    }
    
    public function update_data() {
        
        if ($this->input->exist('change_data') && $this->input->get('change_data') == 'Change data'){
            try{
                $this->valid->validation($_POST);
                $update = $this->Users->update();
                if($update){
                    $this->session->delete_session();
                    throw new Exception('&#x2714; Your data has been modified.');
                }else{
                    throw new Exception('&#x2718; You didn\'t change anything or the email already exist');
                }
            }catch(Exception $e){
                Errors::handle_error2(null,$e->getMessage());
                exit;
            }
        }
    }
    
    public function change_pass() {
        
        if ($this->input->exist('password') && $this->input->get('password') == 'Change'){
            try{
                $this->valid->validation($_POST);
                $this->valid->checkPassword(Sessions::get('user'), $this->input->get('old_pwd'));
                $change = $this->Users->change_password();
                if($change){
                    $this->session->delete_session();
                    throw new Exception('&#x2714; Password changed.');
                }else {
                    throw new Exception('&#x2718; No changes made. There is the same password');
                }
            }  catch (Exception $e){
                Errors::handle_error2(null, $e->getMessage());
                exit;
            }
        }
    }
/**
 * form chenge password
 */
    public function password(){

        $this->view('header');
        $this->view('users/pass_form');
    }
    
//    public function show_profile2($view){
//
//        $row = $this->model('Users', 'get_users', $view);
//        //$vb = setVerb($view);
//        //$pic = self::set_foto_profile($row['profile_pic_id']);
//        include_once '../view/users/show_profile.php';
//    }
    
//    private static function set_foto_profile($row) {
//
//        if (is_null($row)){
//             return SITE_ROOT."/images/missing_user_thb.png";
//        }else {
//             return $row;
//        }
//    }
    
//    private function setDeleteLink($user) {
//
//        if($user != null){
//            return '<a style="float:right" href="'.SITE_ROOT.'/users/delete_pic">delete</a></p>';
//        }
//    }

    public function oath_ajax_login() {
        
        if ($this->input->exist('B') && !empty($_POST['B']) && !empty($_POST['G'])){
            $id = $this->clean_post($this->input->get('B')); //Google ID
            $email = $this->clean_post($this->input->get('G')); // Email ID
            $name = $this->clean_post($this->input->get('ha')); // Name
            $profile_pic = $this->clean_post($this->input->get('wc')); //Profile Pic URL

            $check_user = $this->Users->check_oath($id);
            if (!$check_user)
                $this->Users->insert_oath($name, $email, $id);
            else
                $this->Users->update_oath($name, $email, $id);
            $this->auth_user_oath($name, $id, $profile_pic);
            URL::to("users/login_area");
        }
    }

    private function auth_user_oath($name, $id, $profile_pic){

        $this->session->set_session('user', $name);
        $this->session->set_session('user_id', $id);
        $this->session->set_session('submit', "Welcome");
        $this->session->set_session('id', sha1(K . sha1(session_id(). K)));
        $this->session->set_session('pic', $profile_pic);
    }
    
    private function clean_post($data){
	   $data = trim(strip_tags($data));
	   return $data;
    }
    
    public function oath_fb_login() {

        if ($this->input->exist('id') && !empty($_POST['id'])) {
            extract($_POST);
            $check_fb = $this->Users->check_fb($id);
            if (!$check_fb)
                $this->Users->insert_fb($name, $email, $id);
            else
                $this->Users->update_fb($name, $email, $id);
            $this->auth_user_fb($name, $id);
            URL::to("users/login_area");
        }
    }
    
    private function auth_user_fb($name, $id){

        $this->session->set_session('user', $name);
        $this->session->set_session('user_id', $id);
        $this->session->set_session('submit', "Welcome");
        $this->session->set_session('id', sha1(K . sha1(session_id(). K)));
    }
}