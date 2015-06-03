<?php

final class Admins_C extends Controller{
    
    protected function main(){
        $this->model('Admins');
    }

    public function index() {
        URL::to(SITE_ROOT."/admin/admin.php?class=admins&page=admin");
    }

    public function admin() {
        $this->view('admins/header');
        $this->view('admins/form_login_adm');
    }
    
    public function login() {
        
        if ($this->input->exist('submit') && $this->input->get('submit') == 'Sign In') {
            $this->valid->validation($_POST);
            if($this->session->attempt() === false)
                URL::to(URL::xlink3('admins', 'index', null));
            if(!$this->logins()){
                $this->session->delete_session();
                $this->session->set_session('att', $this->session->get('att') + 1);
                Errors::handle_error2(null,'The username or password does not match!');      
            }
        }
    }
    
    private function logins() {

        $h = new Crypt_HMAC(KEY);
        $adm = $this->Admins->login_admin();
        if($adm){
            $this->auth_admin($adm);
            URL::to(URL::xlink3('admins', 'users', null));
        }else if($this->Admins->login_sup() && $_SESSION['user'] === 'sup'){
            $this->auth_sup();
            URL::to(URL::xlink3('admins', 'users', null));
        }
        return false;
    }

    public function auth_admin($admin){

        foreach($admin as $admin):
            $this->session->set_session('user', $admin->username);
            $this->session->set_session('id', sha1(K1 . sha1(session_id(). K1)));
            $this->session->set_session('track', 1);
        endforeach;    
    }

    public function auth_sup(){
       $this->session->set_session('id', sha1(K1 . sha1(session_id(). K1)));
    }
    
    public function users() {

        $this->view('admins/header');
        if ($this->session->exist('track')) {
            //Registry::setClass('statistic')->tracker();
        }
        $users['users'] = $this->Admins->get_users();
        $users['admins'] = $this->Admins->get_all_admins();
        $this->view('admins/user_list', $users);
    }

    public function register() {
        
        if ($this->input->exist('add_new_admin')  && $this->input->get('add_new_admin') == 'Register'){
            try{
                $this->session->set_session('reg', $_POST);
                $this->valid->validation($_POST);
                //Captcha::responseCaptcha2();
                $reg = $this->Admins->register_admin();
                if($reg){
                    $this->session->delete_session();
                    throw new Exception('&#x2714; Registered!');
                }else{
                     throw new Exception('&#x2718; Register failed');
                }
            }catch(Exception $e){
                 Errors::handle_error2($e->getMessage(), null);
                 exit;
            }
        }
        $this->view('admins/header');
        $this->view('admins/newadmin_form');
    }
    
    public function profile(){
        
        $this->view('admins/header');
        echo "Profile Under construction";
    }
    
    public function visitors() {
        
        $this->view('admins/header');
        echo "Under construction";
    }
    
    public function test(){
    
        $this->view('admins/header');
    	$this->view('admins/test');
    }
}