<?php

class Controller {

    protected $valid;
    protected $session;
    protected $input;
    protected $page;
    protected $id;

    public function __construct() {
        $this->valid = new Validate();
        $this->session = new Sessions();
        $this->input = new Input();
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
            //if(get_url()[0] != 'admins' && !in_array($this->page, array('index', 'login'))):
                //$this->check_hash();
            //endif;
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
        $obj = is_subclass_of($class, 'Controller') && is_a($class, 'Controller') ?
            Registry::getInstance(): new Users_C();
        //$method = $this->getMethod();
        $url = get_url();
        $params = $url ? array_values($url) : [];
        method_exists($obj, $this->page) ? call_user_func_array([$obj, $this->page], $params) : $obj->index();
        //method_exists($obj, $method) ? $obj->$method() : $obj->index();
        //unset($method);
    }
    
    private function getMethod(){

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php": "";
        $method = isset(get_url()[1]) ? get_url()[1] : $config_page['default_page'];
        return $this->valid->check($method);
    }

    protected function getId(){
        
        $id = isset(get_url()[2]) ? get_url()[2] : null;
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
        $array = array('class' => filter_input( INPUT_GET, 'class', FILTER_SANITIZE_STRING ),
                      'page' => $this->page,
                       'id' => $this->id,
                        'h' => filter_input( INPUT_GET, 'h', FILTER_SANITIZE_STRING ));
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
    protected function view1($view, $vars=array()){
        
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

/*
class Controller {

    protected $valid;
    protected $session;
    protected $input;
    protected $page;
    protected $id;

    public function __construct() {
        $this->valid = new Validate();
        $this->session = new Sessions();
        $this->input = new Input();
        $this->page = $this->getMethod();
        $this->id = $this->getId();
        if(method_exists($this, "main")) $this->main();
    }
    
    public function index() {
 
        if(basename($_SERVER['SCRIPT_FILENAME']) == 'index.php'){
            $this->checkUserPage();
        }else if(basename($_SERVER['SCRIPT_FILENAME']) == 'admin.php'){
            if(filter_input(INPUT_GET, 'class', FILTER_SANITIZE_STRING ) != 'admins' &&
               !in_array($this->page, array('index', 'login'))){
                $this->check_hash();
            }
            $this->checkAdminPage();
        }
        $this->view('head');
        $this->dispatch();
        $this->view('footer');
        $this->session->delete_session_messages();
    }
    
    protected function view($templateName, $arrPassValue=''){

        $view_path = APP_PATH . '/view/' . $templateName . '.php';
        if(file_exists($view_path)){
           if(isset($arrPassValue)){
                $arrData = $arrPassValue;
           }
           include_once($view_path);
        }else{
           die($templateName. ' Template Not Found under View Folder');
        }
    }

    protected function model($class){

        $model_path = APP_PATH."/model/$class.php";
        if(file_exists($model_path)){
            include_once($model_path);
        }else{
           die($model_path. ' Model Not Found under Model Folder');
        }
        $class = str_replace('/', '', substr($class, strrpos($class, '/')));
        $this->$class = new $class($_POST);
    }
                
    private function dispatch() {
        
        $class = Registry::setClass();
        if(is_subclass_of($class, 'Controller') && is_a($class, 'Controller')){
            $obj = Registry::getInstance();
        }
        $method = $this->getMethod();

        if(method_exists($obj, $method)){
            $obj->$method();
        }else{
            if(method_exists($obj, "index"))
                $obj->index();
        }
    }
    
    private function getMethod(){

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : "";
        $method = null;
        array_key_exists('page', $_GET) ?
            $method = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) :
            $method = $config_page['default_page'];
        
        return Validate::check($method);
    }

    protected function getId(){
        
        $id = null;
        array_key_exists('id', $_GET) ?
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING) : $id = "";
        return $this->valid->check($id);
    }
    
    private function checkUserPage() {

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : "";

        if(!in_array($this->page, $config_page['all_pages'])){
            URL::to(SITE_ROOT . "/");
            exit;
        }
        if (in_array($this->page, $config_page['page_login'])) {
            if($this->session->checkUser() === false || Users_C::check_user() === false){
                Errors::handle_error2(null,'You must login to see this page.');
                URL::to(SITE_ROOT.'/');
                exit;
            }   
        }
        return $this->valid->check($this->page);
    }   
/*
 * check the hash from every get in admin page
 *
    private function check_hash() {

        $h = new Crypt_HMAC(KEY);
        $array = array('class' => filter_input( INPUT_GET, 'class', FILTER_SANITIZE_STRING ),
                      'page' => $this->page,
                       'id' => $this->id,
                        'h' => filter_input( INPUT_GET, 'h', FILTER_SANITIZE_STRING ));
        if ( !$h->verify_parameters( $array )) {
            die( "Dweep! Somebody tampered with our parameters.\n" );
        }
    }
 
    private function checkAdminPage() {

        file_exists(APP_PATH.'/application/page_rules.php') ?
            include APP_PATH."/application/page_rules.php" : "";

        if (!in_array($this->page, $config_page['admin'])) {
            URL::to(SITE_ROOT . "/admin/admin.php?class=admins&page=index");
            exit;
        }
        if (in_array($this->page, $config_page['login_admin'])) {
            if($this->session->checkAdmin() === false){
                URL::to(SITE_ROOT.'/admin/admin.php?class=admins&page=index');
                exit;
            }
        }
        return $this->valid->check($this->page);
    }*/
    
    /*    
    protected function view1($view, $vars=array()){
        
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
//}