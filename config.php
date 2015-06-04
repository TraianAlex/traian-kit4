<?php

//-------- 1. CONSTANTS define the default path for includes -----------------------------

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
if ($_SERVER['HTTP_HOST'] == 'www.traian4.embassy-pub.ro'){
	define("APP_PATH", dirname(__FILE__));
        define('PATH', '');
        define("SITE_ROOT", "/new-pdo");
        define("PATH_UPL", SITE_ROOT);
        define("PATH_FILE", dirname(__FILE__).SITE_ROOT);
	define("NEW_ADDRESS", "");
        define("BASE", 'http://www.traian4.embassy-pub.ro');
	//define("DEBUG_MODE", false);
	define("DEBUG_MODE", true);//on the cloud change this with false
        
        //ini_set('display_errors', 0);
	ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
	//error_reporting( 0 );
	error_reporting( E_ALL );
}else{
        define('PATH', '/traian-kit4');
	define("APP_PATH", $_SERVER['DOCUMENT_ROOT'].PATH);
        define("SITE_ROOT", PATH."/new-pdo");
        define("PATH_UPL", SITE_ROOT);
        define("PATH_FILE", $_SERVER['DOCUMENT_ROOT'].SITE_ROOT);
	define("NEW_ADDRESS", "http://localhost");
        define("BASE", 'http://localhost/traian-kit4/new-pdo/');
	define("DEBUG_MODE", true);//on the cloud change this with false
        
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
	error_reporting( E_ALL );
}

//--------------- 2. INCLUDES -------------------------------------------------------

file_exists(APP_PATH.'/application/util_func.php') ?
	include_once APP_PATH."/application/util_func.php" : "";

file_exists(APP_PATH.'/library/Core.php') ?
    include_once APP_PATH."/library/Core.php" : "";

if(file_exists(APP_PATH.'/library/recaptchalib.php')){
    require_once APP_PATH.'/library/recaptchalib.php';
}

//------------ 3 INI CLASSES -------------------------------------------------------

Core::initialize();
Sessions::init();
CSRF::init();
Hash::init();

//----------------------4 ERROR REPORTING ---------------------------------------------

if ($_SERVER['HTTP_HOST'] == 'www.traian4.embassy-pub.ro') {
    //set_error_handler('Errors::my_error_handler_prod');
    set_error_handler('Errors::my_error_handler', E_ALL);
    if (isset($_SESSION['system_error_message'])) {
        error_log($_SESSION['system_error_message']."\n", 3,"/home/remb4372/public_html/traian4/new-pdo/error.log");
    }
}else{
    set_error_handler('Errors::my_error_handler', E_ALL);
    if (isset($_SESSION['system_error_message'])) {
       error_log($_SESSION['system_error_message']."\n", 3, "C:\\Users\\victor92\OneDrive\htdocs\\traian-kit4\\new-pdo\\error.log");
    }
}

// ------------ 5 CONFIG KEYS, ENCRYPTION FROM INI FILE ----------------------------

$sec = Config_ini::getConfig("sec");

define('SUP', $sec['sup']);
define('PWD', $sec['pwd']);
define("K", $sec['k']);//for session user
define("K1", $sec['k1']);//for session admin
define("KS", $sec['ks']);//for admin

// Get a key from https://www.google.com/recaptcha/admin/create
define('PUB_KEY', $sec['pub_key']);
define('PRIV_KEY', $sec['priv_key']);

//for hidden input in registration users
define ('FV', crypt($sec['fv1'], '$'.$sec['fv2']));

//for my encryption functions
//define('IV', mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));
define('IV', mcrypt_create_iv(32, MCRYPT_RAND));
define('KY', '&;'.$sec['ky'].'$!');//max8 if >8 error

//for class Encription
define('KYC', '&;$'.$sec['kyc']);//init 10. for encrypt class
define('TYPE', $sec['type']);
define('SEPARATOR', '=');
# - Encryption algorithm name; Recommended algorithms: sha384, sha512, ripemd256, ripemd320, whirlpool or salsa20 ; http://www.php.net/manual/pt_BR/function.hash.php#104987

/* The RFC recommends a key size larger than the output hash for the hash function you use (16 for md5() and 20 for sha1()). */
define('KEY', $sec['key']);

//------- 7 CONFIG CONST FROM XML FILE ------------------------------

//define('DB_INFO', $ftp->getElementsByTagName("db_info")->item(0)->nodeValue);
//define('DB_INFO', $xml->children()[1]->db_info);