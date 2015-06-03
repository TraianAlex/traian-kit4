<?php

class URL{

    public static function to($url){
        
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
            header('Location: ' . $url ) ;
            exit;
        }
    }

    public static function link_l($url, $label, $target=null){
        return '<li><a href="'.$url.'" target='.$target.'>'. $label .'</a></li>';
    }
    
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

        $class = $page = $id = null;
        array_key_exists('class', $_GET)?
            $class = filter_input(INPUT_GET, 'class', FILTER_SANITIZE_STRING):
            $class = 'users';
        array_key_exists('page', $_GET)?
            $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING):
            $page = 'welcome';
        array_key_exists('id', $_GET)?
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING):
            $id = "";
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
        $path = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
        return "<a href='$path?".$h->create_parameters(array('class' => $class, 'page'=>$page,'id'=>$id))."'>$string</a>";
    }
//for sidebar
    public static function xlink2($class, $page, $id, $string){

        $h = new Crypt_HMAC(KEY);
        $path = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
        return "<a $class href='$path?".$h->create_parameters(array('class' => $class, 'page'=>$page,'id'=>$id))."'>$string</a>";
    }

    public static function xlink3($class, $page, $id){

        $h = new Crypt_HMAC(KEY);
        $path = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
        return $path."?".$h->create_parameters(array('class' => $class, 'page' => $page, 'id' => $id));
    }
/**
 * admin
 * @param Crypt_HMAC $h
 * @param type $page
 * @param type $url
 */
    public static function xdelete($class, $page, $id){

        $h = new Crypt_HMAC(KEY);
        return "<a href='{$_SERVER['SCRIPT_NAME']}?".$h->create_parameters(array("class" => $class, "page" => $page,
            "id" => $id))."'><img class='delete_user' src='".SITE_ROOT."/images/delete.png' width=15 alt=''></a>";
    }
}