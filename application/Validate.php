<?php
/**
* @package php-pdo
* validate_class.php
* Validate class file
* Author: Traian Alexandru <victor_traian@yahoo.com>
* @version 1.0 2015-02-03
* @copyright Copyright (c) 2014, All Rights Reserved
* @license GNU General Public License
* @since Since Release 1.0
*/
class Validate {

    public function validation($input = false){

        file_exists(APP_PATH.'/application/validation_rules.php') ?
            include_once APP_PATH."/application/validation_rules.php":
            print "no rules set";
        $formData = [];
        foreach($input as $key => $val):
            $formData[$key] = $val;

            if(in_array($key, $config['emptyKey']))
                $this->isEmpty($formData[$key]);
            
            if(in_array($key, $config['email']))
                $this->ValidateEmail($formData[$key]);//only online
            
            if(in_array($key, $config['size']))
                $this->checkSize($formData[$key], 2, 255);
            
            if(in_array($key, $config['not_allowed']))
                $this->notAllowed($formData[$key], "<>`$()\{}#");
            
            if(in_array($key, $config['user'])):
                $this->ValidateText($formData[$key],3,50,"A-Za-z0-9 '@_.-","a");
                $this->nospace($formData[$key]);
            endif;
            
            if(in_array($key, $config['newuser'])):
                $this->ValidateText($formData[$key],3,50,"A-Za-z0-9 '@_.-","a");
                $this->nospace($formData[$key]);
                $this->confirm_taken($formData[$key]);
            endif;
            
            if(in_array($key, $config['newadmin'])):
                $this->ValidateText($formData[$key],3,50,"A-Za-z0-9 '@_.-","a");
                $this->nospace($formData[$key]);
                $this->confirm_taken_admin($formData[$key]);
            endif;
            
            if(in_array($key, $config['password']))
                $this->ValidateText($formData[$key],4,50,"a-zA-Z0-9 !&+:@~#","");
            
            if(in_array($key, $config['repass'])):
                $pass = $config['password'][0];
                $repass = $config['repass'][0];
                if($formData[$pass] != $formData[$repass])
                  Errors::handle_error2(null,'&#x2718; Type the same password in both fields');
            endif;
            
            if(in_array($key, $config['text']))
                $this->ValidateText($formData[$key], 2, 45, "a-zA-Z0-9 '@_.\-!:", "a");
            
            if(in_array($key, $config['email_exist']))
                $this->emailExists($formData[$key]);
            
            if(in_array($key, $config['email_exist_admin']))
                $this->emailExistsAdm($formData[$key]);
            
            if(in_array($key, $config['forgot_email']))
                $this->emailExists3($formData[$key]);
            
            if(in_array($key, $config['sizemax']))
                $this->checkSize($formData[$key], null, 255);
            
            if(in_array($key, $config['size_max']))
                $this->checkSize($formData[$key], null,14000);
            
            if(in_array($key, $config['format']))
                $this->checkFormat($key, $formData[$key]);
            
            if(in_array($key, $config['digit']))
                $this->ValidateText($formData[$key], 3, 20, "0-9 -", "d");
            
            if(in_array($key, $config['is_taken']))
                $this->confirm_taken_name($formData[$key]);
            
            if(in_array($key, $config['captcha']))
                Captcha::verifyResponse($formData[$key]);
            
            if(in_array($key, $config['captcha1']))
                Captcha::responseCaptcha($formData[$key]);
            
            if(in_array($key, $config['token']))
                CSRF::checkToken($formData[$key]);
            
            if(in_array($key, $config['token1']))
                CSRF::checkTokenInput($formData[$key]);
            
            if(in_array($key, $config['token_url']))
                CSRF::checkTokenUrl($formData[$key]);
            
            if(in_array($key, $config['injection']))
                $this->post_sec2($formData[$key]);
        endforeach;
    }

    private function checkFormat($field, $value) {

        switch ($field):
            case 'adresa':
                 $regex = "/^(http|https|ftp):\/\/(www\.)?[\w-]+\.([A-Za-z0-9]{2,4})\/?/i";
                 $msg = "Address invalid";
                 break;
            case 'pm':
                 $regex = "/^([1|2])+$/i";
                 $msg = "Choose private or public";
                 break;
            case 'in':
                 $regex = "/^([firstname|feedback|titlu|adresa|descriere])+$/i";
                 $msg = "Choose an option";
                 break;
            case 'confirm':
                 $regex = "/^([Yes|No])+$/i";
                 $msg = "Choose Yes or No";
                 break;
             case 's_q':
                 $regex = "/^([2-8])+$/i";
                 $msg = "Please responde at the answer correctly!";
                 break;
             case 'page_id'://for empty field in gallery for article
             case 'article_id':
             case 'image_id':
                 $regex = "/^([\d])+$/i";
                 $msg = "Please select from the list!";
                 break;
            default:
                 $regex = "/^([a-zA_Z0-9 \.])+$/i";
                 $msg = 'I can\'t locate the field';
        endswitch;

        if (!preg_match($regex, trim($value)))
            Errors::handle_error2(null, $msg);
    }
/**
 * check if a input is empty
 * @param string $value
 */
    private function isEmpty($value) {

        $value = trim($value);
        if (empty($value))
            Errors::handle_error2(null,'&#x2718; Fields may not be empty.');
    }
/**
 * check the min and max for input
 * @param string $value
 * @param int $minLength
 * @param int $maxLength
 */
    private function checkSize($value, $minLength, $maxLength) {

        $value = trim($value);
        if (strlen($value) < $minLength || strlen($value) > $maxLength)
            Errors::handle_error2(null,'&#x2718; Incorrect size.');
    }
/**
 * check after duplicate emails at registration
 * check email and skip by curent user when try to change the email
 * @param string $email
 */
    private function emailExists($email) {

        if(Users::get_email($email))
            Errors::handle_error2(null,'&#x2718; The email already exist!');
    }
    
    private function emailExistsAdm($email) {

        if(Admins::get_email_adm($email))
            Errors::handle_error2(null,'&#x2718; The email already exist!');
    }
/**
 * confirmation that a email is registered for a pass recover
 * @param string $email
 */
    private function emailExists3($email) {

       if(!Users::get_email($email))
          Errors::handle_error2(null,'&#x2718; This email is not registered!');
    }
/**
 * username do not contain spaces
 * @param string $one
 */
    private function nospace($user){

        if (preg_match('/\s/', $user))
            Errors::handle_error2(null,'&#x2718; Username should not contain spaces.');
    }
/**
 * confirm if a user name has been taken
 * @param string $one
 */
    private function confirm_taken($one){

        if(Users::get_users($one))
           Errors::handle_error2(null,'&#x2718; This name has been taken. Please try another!');
    }
    
    private function confirm_taken_admin($one){

        if(Admins::get_admins($one))
           Errors::handle_error2(null,'&#x2718; This name has been taken. Please try another!');
    }
    
    private function confirm_taken_name($one){

        if(Ajax::get_name($one))
           Errors::handle_error2(null,'&#x2718; This name has been taken. Please try another!');
    }
/*
 * check a valid email and a valid domain name
 * @param string $email
 * @return boolean
 */
    private function ValidateEmail($email){
   // This plug-in takes an email address and determines whether
   // it appears to be valid. The argument required is:
   //
   //    $email: An email address to validate
       $at = strrpos($email, '@');

       if (!$at || strlen($email) < 6)
           Errors::handle_error2(null,'&#x2718; Email address invalid');

       $left  = substr($email, 0, $at);
       $right = substr($email, $at + 1);
       $res1  = $this->ValidateText($left,  1, 64,  "\w\.\+\-", "a");
       $res2  = $this->ValidateText($right, 1, 190, "\a-zA-Z0-9\.\-", "a");
       $valid = checkdnsrr($right, "ANY");//check for valid domain

       if (!strpos($right, '.') || !$res1 || !$res2 || !$valid)
          Errors::handle_error2(null,'&#x2718; Email address invalid');
       else return TRUE;
}
/**
 * check after a valid text
 * @param string $text
 * @param int $minlength
 * @param int $maxlength
 * @param string $allowed
 * @param string $required
 * @return boolean
 */
    private function ValidateText($text, $minlength, $maxlength, $allowed, $required){
   // This plug-in takes a string and parameters defining its
   // minimum and maximum length, and the allowed characters.
   // The arguments are:
   //    $text:      The text to be validate
   //    $minlength: The minimum allowed length
   //    $maxlength: The maximum allowed length
   //    $allowed:   The allowed characters. Can include regexp
   //                strings such as a-zA-Z0-9 or \w. Characters
   //                used in regular expressions but which are
   //                to be allowed (such as ( and [ etc) should
   //                be escaped, like this: \( and \[.
   //    $required:  The required characters. This argument
   //                is a string containing one or more of the
   //                letters a, l, u, d, w or p for any letter,
   //                lower case, upper case, digit, word (any
   //                lower, upper, digit or _) or punctuation.
   //                For each of these included, at least one of
   //                that type of character must be in the string
   // The plug-in returns an array of two elements if the string
   // does not validate. The first has the value FALSE and the
   // second is an array of error messages. If it does validate
   // Only one element is returned and its value is TRUE.

       $len   = strlen($text);
       $error = [];

       if ($len < $minlength)
          $error[] = "The string length is too short (min $minlength characters)";
       elseif ($len > $maxlength)
          $error[] = "The string length is too long (max $maxlength characters)";

       $result = preg_match_all("/([^$allowed])/", $text, $matches);
       $caught = implode(array_unique($matches[1]), ', ');
       $plural = strlen($caught) > 1 ? $plural = "s are" : " is";

       if ($result) $error[] = "The following character$plural not allowed: " . $caught;

       for ($j = 0 ; $j < strlen($required) ; ++$j){
          switch(substr(strtolower($required), $j, 1)){
             case "a": $regex = "a-zA-Z"; $str = "letter";
                       break;
             case "l": $regex = "a-z";    $str = "lower case";
                       break;
             case "u": $regex = "A-Z";    $str = "upper case";
                       break;
             case "d": $regex = "0-9";    $str = "digit";
                       break;
             case "w": $regex = "\w";     $str = "letter, number or _";
                       break;
             case "p": $regex = "\W";     $str = "punctuation";
                       break;
          }

          if (!preg_match("/[$regex]/", $text))
                $error[] = "The string must include at least one $str character";
        }

       if (count($error)) {
            for ($j = 0; $j < count($error); ++$j) {
                Errors::handle_error2(null, '&#x2718; ' . $error[$j]);
                exit;
                //[FALSE, $error];
            }
        } else {
            return TRUE;
        }
    }
/**
 * check if a bad char is in string. if any of them find exit
 * @param string $string
 * @param string $notallow
 */
    private function notAllowed($string, $notallow){

        if (strpbrk($string, $notallow))
            Errors::handle_error2(null,"&#x2718; $notallow are not allowed in $string");
    } 
/**
 * check password when the user want to change it
 * @param obj $user, string $session_user and $pwd
 * @param string $pwd
 */
   public function checkPassword($session_user, $pwd) {
       
        $row = Users::checkHashConfirmation($session_user);
        $res = Hash::validate_password($pwd, PBKDF2_HASH_ALGORITHM.':'.PBKDF2_ITERATIONS.':'.
                                    $row['salt'].':'.$row['password']);
        if(!$res)
            Errors::handle_error2(null,'&#x2718; Current password incorrect');
    }
/*
 * check class, method, page, id in registry, router, controller
 * not . or .. //strstr($page, '.')strstr($page, '/')strstr($page, '\\')
 */   
    public static function check($page){
        
        if (!preg_match('/^[a-z0-9-_]+$/i', $page) && $page != null)
            Errors::handle_error2('Unsafe page ' . $page . ' requested', null);
        return $page;
    }
/*
 * check choosen input post from forms
 */
    public function post_sec2($data){

        if(isset($data) && empty($data) === false)
            for ($j = 0; $j < sizeof(self::$dis); ++$j)
                if(preg_match (',' . self::$dis[$j] . ',', strtolower($data)))
                    Errors::handle_error2(null, 'Please use another word!');
    }
/*
 * check any get input from url
 */    
    public static function get_sec2($array){

        if(isset($array))
          for ($i = 0; $i < sizeof($array); ++$i)
            for ($j = 0; $j < sizeof(self::$dis); ++$j)
              foreach($array as $gets)
                if(preg_match (',^' . self::$dis[$j] . '$,', strtolower($gets)))
                  Errors::handle_error2(null, 'Please use another word!');
    }
/*---------------------------------------------------------------------------------------*/
    public function post_sec(){

      if(isset($_POST))
        for ($i = 0; $i < sizeof($_POST); ++$i)
          for ($j = 0; $j < sizeof(self::$dis); ++$j)
            foreach($_POST as $gets)
              if(preg_match (',' . self::$dis[$j] . ',', strtolower($gets)))
                Errors::handle_error2(null, 'Please use another word!');
    }
    
    public function get_sec(){

      if(isset($_GET))
        for ($i = 0; $i < sizeof($_GET); ++$i)
          for ($j = 0; $j < sizeof(self::$dis); ++$j)
            foreach($_GET as $gets)
              if(preg_match (',' . self::$dis[$j] . ',', strtolower($gets)))
                Errors::handle_error2(null, 'Please use another word!');
    }
    
    public function full_sec(){

        if(isset($_REQUEST))
          for ($i = 0; $i < sizeof($_REQUEST); ++$i)
            for ($j = 0; $j < sizeof(self::$dis); ++$j)
              foreach($_REQUEST as $gets)
                if(preg_match (',' . self::$dis[$j] . ',', strtolower($gets)))
                  Errors::handle_error2(null, 'Please use another word!');
    }
    
    private static $dis = ['select', 'insert', 'delete', 'update','drop table', 'union',
              'null','order by','order+by','from','version','database','tables','query',
              '<','>','<script','/>'];
}