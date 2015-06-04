<?php

class Controller {

    protected $valid;
    protected $session;
    protected $input;
    protected $route = [];
    protected $page;
    protected $id;

    public function __construct() {
        $this->valid = new Validate();
        $this->session = new Sessions();
        $this->input = new Input();
        $this->route = get_url();
        $this->page = $this->getMethod();
        $this->id = $this->getId();
        if(method_exists($this, "main")) $this->main();
    }
    
    public function index() {
 
        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php": "";
        if(in_array($this->page, $config_page['all_pages'])):
            $this->checkUserPage();
        elseif(in_array($this->page, $config_page['admin'])):
            if($this->route[0] != 'admins' && !in_array($this->page, ['index', 'login'])):
                $this->check_hash();
            endif;
            $this->checkAdminPage();
        endif;
        $this->view('head');
        $this->dispatch();
        $this->view('footer');
        $this->session->delete_session_messages();
    }
    
    protected function view($templateName, $arrPassValue=''){

        $view_path = APP_PATH . '/view/' . $templateName . '.php';
        if(file_exists($view_path)):
            $arrData = isset($arrPassValue) ? $arrPassValue : '';
            include_once($view_path);
        else:
           die($templateName. ' Template Not Found under View Folder');
        endif;
    }

    protected function model($class){

        $model_path = APP_PATH."/model/$class.php";
        file_exists($model_path) ?
            include_once($model_path):
            die($model_path. 'Model Not Found under Model Folder');
        $class = str_replace('/', '', substr($class, strrpos($class, '/')));
        $this->$class = new $class($_POST);
        unset($class);
    }
                
    private function dispatch() {
        
        $class = Registry::setClass();
        $controller = is_subclass_of($class, 'Controller') && is_a($class, 'Controller') ?
            Registry::getInstance(): new Users_C();
        $params = $this->route ? array_values($this->route) : [];
        method_exists($controller, $this->page) ? call_user_func_array([$controller, $this->page], $params) : $controller->index();
        //method_exists($controller, $this->page) ? $controller->$this->page() : $controller->index();
    }
    
    private function getMethod(){

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php": "";
        $method = isset($this->route[1]) ? $this->route[1] : $config_page['default_page'];
        return $this->valid->check($method);
    }

    protected function getId(){
        
        $id = isset($this->route[2]) ? $this->valid->post_sec2($this->route[2]) : null;
        return $this->valid->check($id);
    }
    
    private function checkUserPage() {

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : "";
        if(!in_array($this->page, $config_page['all_pages']))
            URL::to(SITE_ROOT . "/");
        if (in_array($this->page, $config_page['page_login']) &&
           ($this->session->checkUser() === false)): //|| Users_C::check_user() === false)):
            Errors::handle_error2(null,'You must login to see this page.');
            URL::to(SITE_ROOT.'/');
        endif;
        return $this->valid->check($this->page);
    }   
/*
 * check the hash from every get in admin page
 */
    private function check_hash() {

        $h = new Crypt_HMAC(KEY);
        $array = ['class' => $this->route[0],
                  'page' => $this->page,
                    'id' => $this->id,
                     'h' => isset($this->route[3]) ? $this->route[3] : null];
        if ( !$h->verify_parameters( $array ) && DEBUG_MODE === true):
            die( "Dweep! Somebody tampered with our parameters.\n" );
        elseif (!$h->verify_parameters( $array ) && DEBUG_MODE === false):
            URL::to(SITE_ROOT . "/admin/admin.php?class=admins&page=index");
        endif;
    }
 
    private function checkAdminPage() {

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : "";
        if (!in_array($this->page, $config_page['admin']))
            URL::to(SITE_ROOT . "/admins");
        if (in_array($this->page, $config_page['login_admin']) && $this->session->checkAdmin() === false):
            Errors::handle_error2(null,'You must login to see this page.');
            URL::to(SITE_ROOT.'/admins');
        endif;
        return $this->valid->check($this->page);
    }
    
    /*    
    protected function view1($view, $vars=[]){
        
        $view = str_replace('.tpl.php', '', $view);
        $GLOBALS['output'] = ob_get_contents();
        $GLOBALS = array_merge($GLOBALS, $vars);
        @ob_end_clean();
        include("$view.tpl.php");
    }*/
    //ex. $reg = $this->model1('Users', 'register_user');
    /*protected function model($modelName, $function, $arrArgument=''){

        $model_path = APP_PATH . '/model/' . $modelName . '.php';
        if(file_exists($model_path)){
           if(isset($arrArgument)){
                $arrData = $arrArgument;
           }
           include_once($model_path);
           if(!method_exists($modelName, $function)){
               die($function . ' function not found in Model ' . $modelName);
           }
           $obj = new $modelName($_POST);
           if(isset($arrArgument)){
               return $obj->$function($arrArgument);
           }else{
               return $obj->$function();
           }
        }else{
           die($modelName. ' Model Not Found under Model Folder');
        }
    }*/
}