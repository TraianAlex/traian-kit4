<?php

//----- CONFIG VALIDATION RULES ----------------------------------------------------

//      1. register the name of controllers for post validation
/*
 *  check is a control defined in array is empty - required
 */
       $config['emptyKey'] = ['user', 'pwd', 'repwd', 'newuser', 'email', 's_q', 'f_email', 'p_email',
                              ];
/*
 * check is an email is valid (only online)
 */
       $config['email'] = ['email', 'f_email', 'p_email', 'email_adm'];
/*
 * check is an email exist at registration or changing email.
 */
       $config['email_exist'] = ['email', 'p_email'];
       $config['email_exist_admin'] = ['email_adm'];
/*
 * check if email exist (is registered) at password recovering
 */
       $config['forgot_email'] = ['f_email'];
/*
 *  check if the size is between 2 and 255
 */
       $config['size'] = array();
/*
 * check if exist not allowed chars like "<>`$()\{}#" 
 */  
       $config['not_allowed'] = array();
/*
 * check if the size are between o and 255. focused for the max size and can be empty
 */
       $config['sizemax'] = array();
/*
 * only text validation like min=2, max=45, allowed="a-zA-Z0-9 '@_.\-!:", required="a"
 * The required characters. This argument is a string containing one or more of the
   letters a, l, u, d, w or p for any letter, lower case, upper case, digit, word (any
   lower, upper, digit or _) or punctuation. For each of these included, at least one of
   that type of character must be in the string
 */
       $config['text'] = array();
/*
 * in validation.php we can set suplimentary rules
 * case 'adresa':
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
 */
       $config['format'] = ['s_q'];
/*
 * check the validation text like $config['text'] but min=3 max=50
 * no space
 */
       $config['user'] = ['user'];
/*
 * check the validation text like $config['text'] but min=3 max=50
 * check if the name is already taken (the second checking after ajax)
 * check is an user is banned
 */
       $config['newuser'] = ['newuser'];
       $config['newadmin'] = ['newadmin'];
/*
 * validation text like $config['text'] but with different rules like
 * min = 4, max = 50, allowed = "a-zA-Z0-9 !&*+=:;@~#", required = ""
 * use only one member for this array
 */
       $config['password'] = ['pwd'];
/*
 * check is the password is the same in retype
 * use only one member for this array
 * if we want to put another set of passwords we must modify in validate.php the offset type
 * and make another set of rules with index 1
 */
       $config['repass'] = ['repwd'];
/*
 * check if the size are between o and 14000. focused for the max size and can be empty
 * it is not restricted for not allowed chars
 */
       $config['size_max'] = array();
/*
 * validate text but only digit and dash
 * min = 3, max = 20, allowed = "0-9 -", required = "d"
 */
       $config['digit'] = array();
/*
 * check if a name or other item (first define a function in model and in Validate)
 */
       $config['is_taken'] = array();
/*
 * anti spam rule like captcha. if s_q field exist than Captcha::verfyResponse($_POST['s_q']) execute
 */
       $config['captcha'] = ['s_q'];
       $config['captcha1'] = ['recaptcha_challenge_field'];
/*
 * CSRF protection. if a field with v or v1 name exist than CSRF::chechToken($_POST['v']) execute
 */      
       $config['token']    = ['v'];
       $config['token1']   = ['v1'];
       $config['token_url'] = [];
/*
 * SQL injection and cross-site scripting. select the input area for post method
 * 'select', 'insert', 'delete', 'update','drop table', 'union', 'null','order by','order+by',
 * 'from','version','database','tables','query', '<','>','<script','/>'
*/       
       $config['injection'] = ['user', 'pwd', 'repwd', 'newuser', 'newadmin',
                                'email', 's_q', 'f_email', 'p_email', 'email_adm'];