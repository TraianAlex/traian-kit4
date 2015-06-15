<?php

class URL{

    private $base_url;
    private $parameters;
    private $file;
    private $text;

    public function __construct(){
        $this->base_url = SITE_ROOT;
    }

    public function add_param($param_val){
        $this->parameters = $param_val;
    }

    public function set_file($value){
        $this->file = $value;
    }

    public function set_text($value){
        $this->text = $value;
    }

    public function render(){
        $r = '';
        if(count($this->parameters))
            $r .= $this->base_url .'/'. $this->parameters;
        if(trim($this->file) != '')
            $r .= '/'. $this->file;
        return $r;
    }

    public function r_link(){
        if(trim($this->text) == '')
            $text = $this->render();
        else
            $text = $this->text;
        return '<a href="'.$this->render().'">'.$text.'</a>';
    }

    public static function link($param, $text=null, $file=null){

        $url = new URL();
        $url->set_file($file);
        $url->set_text($text);
        if(count($param) > 0){
            $url->add_param($param);
        }
        return $url->r_link();
    }
/*
* redirect url
*/
    public static function to($url=null){
        if($url){
            if(is_numeric($url)){
                switch ($url) {
                    case 404:
                        header('HTTP1/0 404 Not Found') ;
                        include SITE_ROOT.'/show_error.php';
                        exit();
                    break;
                    default:
                        header('Location: ' . SITE_ROOT ) ;
                    break;
                }
            }
            header('Location: ' . SITE_ROOT .'/'. $url ) ;
            exit;
        }else{
            header('Location: ' . SITE_ROOT .'/' ) ;
            exit;
        }
    }
//link <li>
    public static function link_l($url, $label, $target=null){
        return '<li><a href="'.$url.'" target='.$target.'>'. $label .'</a></li>';
    }
//simple link
    public static function link_s($url, $label, $target=null){
        return "<a href=".$url." target=".$target.">$label</a>";
    }
/**
 * create a horizontal menu
 * @param string $siteURL
 * @param string $section
 * @param string $title
 */
     public static function create_crumbs() {

        $class = isset(get_url()[0]) ? get_url()[0] : 'users';
        $page = isset(get_url()[1]) ? get_url()[1] : 'index';
        $id = isset(get_url()[2]) ? get_url()[2] : null;
        return " <ol class='breadcrumb'>"
                . "<li>".self::back()."</li>"
                . "<li><a href ='".SITE_ROOT."/users/nologin_area'>Home</a></li> "
                . "<li><a href ='".SITE_ROOT."/$class/$page'>$page</a> $id</li>"
             . "<ol>";
     }
/**
 * back link
 * 1 check that browser supports $_SERVER variables
 * 3 find if visitor was referred from a different domain
 * 4 if same domain, use referring URL
 * 7 otherwise, send to main page
 */
    private static function back() {

        if (isset($_SERVER['HTTP_REFERER']) && isset($_SERVER['HTTP_HOST'])):
            $url2 = parse_url($_SERVER['HTTP_REFERER']);
            if ($url2['host'] == $_SERVER['HTTP_HOST']):
                $back = $_SERVER['HTTP_REFERER'];
            endif;
        else:
            $back = SITE_ROOT.'/';
        endif;
        return "< <a href='$back'>Back</a> ";
    }
/**
 * admin
 * @param Crypt_HMAC $h
 * @param type $path
 * @param type $page
 * @param type $url
 * @param type $string
 */
    public static function xlink($class, $page, $id, $string){

        $h = new Crypt_HMAC(KEY);
        return "<a href='".SITE_ROOT."/".$h->create_parameters(array('class' => $class, 'page'=>$page,'id'=>$id))."'>$string</a>";
    }
//for sidebar
    public static function xlink2($class, $page, $id, $string){

        $h = new Crypt_HMAC(KEY);
        return "<a $class href='".SITE_ROOT."/".$h->create_parameters(array('class' => $class, 'page'=>$page,'id'=>$id))."'>$string</a>";
    }

    public static function xlink3($class, $page, $id){

        $h = new Crypt_HMAC(KEY);
        return $h->create_parameters(array('class' => $class, 'page' => $page, 'id' => $id));
    }
/**
 * admin
 * @param Crypt_HMAC $h
 * @param type $page
 * @param type $url
 */
    public static function xdelete($class, $page, $id){

        $h = new Crypt_HMAC(KEY);
        return "<a href='".SITE_ROOT."/".$h->create_parameters(array("class" => $class, "page" => $page,
            "id" => $id))."'><img class='delete_user' src='".SITE_ROOT."/images/delete.png' width=15 alt=''></a>";
    }
}