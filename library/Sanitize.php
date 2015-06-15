<?php
/**
* @package php-pdo
* sanitize_class.php
* Sanitize class file
* Author: Traian Alexandru <victor_traian@yahoo.com>
* @version 1.0 2014-01-07
* @copyright Copyright (c) 2014, All Rights Reserved
* @license GNU General Public License
* @since Since Release 1.0
*/

class Sanitize{

 /* similar as filter_input_array or filter_var_array but
 * added the type, required and function
 * redirect if no name exist
 * sanitize where need a customatization in sanitizing input
 */
    static function sanitize_vars(&$vars, $sigs, $redir_url = true) {

        $tmp = array();
        foreach ($sigs as $name => $sig) {/*Walk through the signatures and add them to the temporary array $tmp */
         if (!isset($vars[$name]) && isset($sig['required']) && $sig['required']) {
                if ($redir_url == true) {//redirect if the variable doesn't exist in the ar
                    URL::to();
                } else {
                        echo 'Parameter $name not present and no redirect URL';
                }
                exit();
            }

            $tmp[$name] = $vars[$name];
            /* apply type to variable */
            if (isset($sig['type'])) {
                settype($tmp[$name], $sig['type']);
            }
    /* apply functions to the variables, you can use the standard PHP functions,or your own for added flexibility. */
            if (isset($sig['function'])) {
                $tmp[$name] = $sig['function']($tmp[$name]);
            }
        }
        $vars = $tmp;
    }
/**
 * sanitize input val
 * @param type $data
 * @return string or array
 */
    static function sanitizeData($data) {//array and string
        
        $data = !is_array($data) ? htmlentities($data) : array_map('sanitizeData', $data);
        return $data;
    }
/*
 * it's working for val
 */
    static function stripslashes_deep($value){
        
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    htmlspecialchars(stripslashes($value));
        return $value;
    }
/*
 * it's working for val
 */
    static function striptags($value){

        if(isset($value) && !empty($value)){
            $value = is_array($value) ?
                    array_map('striptags', $value) :
                    strip_tags($value);
            return $value;
        }
    }
/**
 * only arrays $_POST,$_GET,$_REQUEST,$_COOKIE and others inputs
 * result only val. after that it can't use the key (invalid arg for foreach)
 * @param array $arr
 */
    static function stripslashes_array(&$arr) {

        foreach ($arr as $k => &$v) {
            $nk = stripslashes($k);
            if ($nk != $k) {
                $arr[$nk] = &$v;
                unset($arr[$k]);
            }
            if (is_array($v)) {
                self::stripslashes_array($v);
            } else {
                $arr[$nk] = stripslashes($v);
            }
        }
    }
/**
 * apply function allowed for string (wrapp a string)
 * @param array $input
 * @param string $func
 * @return string
 */
    static function sanit($input, $func){

        $str = new CleverString();
        if(is_array($input)){
            foreach ($input as $v) {
                $str->setString($v);
                echo $str->$func();
            }
        }else{
            $str->setString($input);
            echo $str->$func();
        }
    }
/**
 * sanitize the ouput
 * @param string $text
 */
    static function htmlout($text) {
        return htmlentities($text, ENT_QUOTES, 'UTF-8');//htmlspecialchars
    }
/**
 * replace all bad word and put the smiles emoticons
 * @param string $txt
 * @return string
 */
    static function format($txt) {
        
        $de_inloc = $cu_inloc = [];
        $de_inloc[] = ["prost", "cacat", "pisat", "pula", "sula", "pizda", "muie", "sugi", "coi", "coios", "taran", "curva", "jepat", "fuck", "futut", "fut", "fute", "muist", "bulesc", "babardesc", "homo", "homosexual", "bulit", "futi", "floci", "bagami-as", "bagamias", "mata", "tact-o", 'bitch', "bitchass", "blowjob", "blow job", "shit"];
        $cu_inloc[] = "<span style=\"color:red; font-style: italic;\">×××</span>";
        $de_inloc[] = ":))";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_1.gif\" align=\"middle\" alt=\"&#058;&#041;&#041;\" title=\"&#058;&#041;&#041;\" border=\"0\">";
        $de_inloc[] = ">:)";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_2.gif\" align=\"middle\" alt=\"&#062;&#058;&#041;\" title=\"&#062;&#058;&#041;\" border=\"0\">";
        $de_inloc[] = ":)";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_3.gif\" align=\"middle\" alt=\"&#058;&#041;\" title=\"&#058;&#041;\" border=\"0\">";
        $de_inloc[] = ";)";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_4.gif\" align=\"middle\" alt=\"&#059;&#041;\" title=\"&#059;&#041;\" border=\"0\">";
        $de_inloc[] = ":\(";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_5.gif\" align=\"middle\" alt=\"&#058;&#040;\" title=\"&#058;&#040;\" border=\"0\">";
        $de_inloc[] = ":~";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_6.gif\" align=\"middle\" alt=\"&#058;&#126;\" title=\"&#058;&#126;\" border=\"0\">";
        $de_inloc[] = ":&quot;>";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_7.gif\" align=\"middle\" alt=\"&#058;&#034;&#062;\" title=\"&#058;&#034;&#062;\" border=\"0\">";
        $de_inloc[] = ":o";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_8.gif\" align=\"middle\" alt=\"&#058;o\" title=\"&#058;o\" border=\"0\">";
        $de_inloc[] = ":ups";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_9.gif\" align=\"middle\" alt=\"&#058;ups\" title=\"&#058;ups\" border=\"0\">";
        $de_inloc[] = "B-)";
        $cu_inloc[] = "<img src=\"" . SITE_ROOT . "/admin/images/zambareti/zambaret_10.gif\" align=\"middle\" alt=\"B&#045;&#041;\" title=\"B&#045;&#041;\" border=\"0\">";
        $de_inloc[] = "\[b\]";
        $cu_inloc[] = "<strong>";
        $de_inloc[] = "\[/b\]";
        $cu_inloc[] = "</strong>";
        $de_inloc[] = "\[i\]";
        $cu_inloc[] = "<i>";
        $de_inloc[] = "\[/i\]";
        $cu_inloc[] = "</i>";
        $de_inloc[] = "\[u\]";
        $cu_inloc[] = "<u>";
        $de_inloc[] = "\[/u\]";
        $cu_inloc[] = "</u>";
        $de_inloc[] = "\\[url\\]([^\\[]*)\\[/url\\]";
        $cu_inloc[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\1</a>";
        $de_inloc[] = "\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]";
        $cu_inloc[] = "<a href=\"\\1\" target=\"_blank\" title=\"\\1\">\\2</a>";
        $de_inloc[] = "\\[img\\]([^\\[]*)\\[/img\\]";
        $cu_inloc[] = "<img src=\"\\1\" alt=\"\\1\" title=\"\\1\">";
        $de_inloc[] = "\\[img=([^\\[]*)\\]([^\\[]*)\\[/img\\]";
        $cu_inloc[] = "<img src=\"\\1\" alt=\"\\2\" title=\"\\2\">";

        foreach ($de_inloc as $nume => $valoare) {
            $txt = str_replace($valoare, $cu_inloc[$nume], $txt);
        }
        $txt = nl2br($txt);
        return $txt;
    }

    function change_words($txt){
        $pattern[] = "/prost|cacat|pisat|pula|sula|pizda|muie|sugi|coi|coios|taran|curva|jepat|fuck|futut|fut|fute|muist|bulesc|babardesc|homo|homosexual|bulit|futi|floci|bagami-as|bagamias|mata|tact-o|bitch|bitchass|blowjob|blow job|shit/";
        $replace[] = "<span style=\"color:red; font-style: italic;\">×××</span>";
        if(is_array($txt) && count($txt) > 0){
            foreach ($txt as $value) {
                return preg_replace($pattern, $replace, $value);
            }
        }else{
            return preg_replace($pattern, $replace, $txt);
        }
    }

    public static function add($array){

        $ret = array();
        foreach ($array as $key => $value){
            if (is_array($value)){
                $ret[$key] = array_merge($ret[$key], $this->add($value));
                continue;
            }
            $ret[$key] = addslashes($value);
        }
        return $ret;
    }

    public static function strip($array){
 
        $ret = array();
        foreach ($array as $key => $value){
            if (is_array($value)){
                $ret[$key] = array_merge($ret[$key], $this->strip($value));
                continue;
            }
            $ret[$key] = stripslashes($value);
        }
        return $ret;
    }

    public function array_sanitize(&$item){
        $item = htmlentities(strip_tags(stripslashes($item)));
    }
    
    public function sanitize_data($item){
        return htmlentities(strip_tags(stripslashes($item)));
    }
}

//array_walk_recursive($_POST, create_function('&$val', '$val=stripslashes($val);'));
//array_walk($_POST, 'array_sanitize');