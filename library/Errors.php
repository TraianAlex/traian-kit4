<?php

class Errors{

    public static function flash($name, $message='') {
        if(Sessions::exist($name)){
            $session = Sessions::get($name);
            Sessions::delete($name);
            return $session;
        }else{
            Sessions::set_session($name, $message);
        }
    }

    public static function show_errors($success_msg=null, $error_msg=null) {
        if(Sessions::exist('success_msg') || Sessions::exist('error_msg'))
            return self::display_messages(Sessions::get('success_msg'), Sessions::get('error_msg'));
    }

    private static function display_messages($success_msg = NULL, $error_msg = NULL) {
        if (!empty($success_msg))
            self::display_message($success_msg, 'success');
        if (!empty($error_msg))
            self::display_message($error_msg, 'error');
    }

    private static function display_message($msg, $msg_type) {
        echo " <div class='{$msg_type}'><p>{$msg}</p>\n</div>";
    }

    public static function handle_error($user_error_message, $system_error_message) {
        Sessions::set_session('error_message', $user_error_message);
        Sessions::set_session('system_error_message', $system_error_message);
        URL::to(SITE_ROOT . "/show_error.php");
        exit();
    }

    public static function handle_error2($success_msg, $error_msg) {
        Sessions::set_session('success_msg', $success_msg);
        Sessions::set_session('error_msg', $error_msg);
        if (!isset($_SERVER['HTTP_REFERER']))
            $_SERVER['HTTP_REFERER'] = SITE_ROOT . "/";
        URL::to($_SERVER['HTTP_REFERER']);
        return; //return to check admin after user
    }

    public static function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {
        $message = "script '$e_file'\nline $e_line\n$e_message\n";
        $message .= print_r($e_vars, 1); // Append $e_vars to $message:
        if (DEBUG_MODE) { // Development (print the error).
            echo '<pre>' . $message . "\n";
            debug_print_backtrace();
            echo '</pre><br />';
            die();
        } else { // Don't show the error.
            die('<div class="error">A system error occurred. We apologize for the inconvenience.</div><br />');
        }
    }

    public static function my_error_handler_prod() {
        die('<div class="error">A system error occurred. We apologize for the inconvenience.</div><br />');
    }

    function dflt_handler(Exception $e) {//it is not used yet
        print "Exception:\n";
        $code = $e->getCode();
        if (!empty($code)) printf("Erorr code:%d\n", $code);
        print $e->getMessage() . "\n";
        print "File:" . $e->getFile() . "\n";
        print "Line:" . $e->getLine() . "\n";
        exit(-1);
    }

    function paranoidHandler( $errno, $errstr, $errfile, $errline, $errcontext ) {
        $levels = array (
        E_WARNING => "Warning",             E_USER_DEPRECATED => "Deprecated feature",
        E_USER_ERROR => "Error",            E_USER_WARNING => "Warning",
        E_USER_NOTICE => "Notice",          E_STRICT => "Strict warning",
        E_NOTICE => "Notice",               E_RECOVERABLE_ERROR => "Recoverable error",
        E_DEPRECATED => "Deprecated feature");
        $message = date( "Y-m-d H:i:s - " );
        $message .= $levels[$errno] . ": $errstr in $errfile, line $errline\n\n";
        $message .= "Variables:\n";
        $message .= print_r( $errcontext, true ) . "\n\n";
        error_log( $message, 3, "C:\\xampp\htdocs\php-pdo\admin\\error.log" );
        die( "There was a problem, so I’ve stopped running. Please try again." );
    }

    function handleMissedException($e) {
        echo "Sorry, something is wrong. Please try again, or contact us.
         if the problem persists";
        error_log('Unhandled Exception: ' . $e->getMessage()
        . ' in file ' . $e->getFile() . ' on line ' . $e->getLine());
    }
//set_exception_handler('handleMissedException');
//param 1 by mail, 3 write in the error log
//error_log("Houston, we've had a problem.", 1, "victor_traian@yahoo.com", "Cc: victor_traian@hotmail.com" );
}