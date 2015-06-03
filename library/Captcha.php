<?php

class Captcha{
    
    public static function verifyResponse($resp){// Grab the session value and destroy it

        $val = (int)$_SESSION['challenge'];
        unset($_SESSION['challenge']);
        if($resp != $val){// Returns TRUE if equal, FALSE otherwise
            Errors::handle_error2(null,'&#x2718; Please answer the anti-spam question correctly!');
            exit;
        }
    }

    public static function generateChallenge(){

        $numbers = array(mt_rand(1,4), mt_rand(1,4));// Store two random numbers in an array
        $_SESSION['challenge'] = $numbers[0] + $numbers[1];//Store the correct answer in a session
        $converted = array_map('ord', $numbers);//Convert the numbers to their ASCII codes
        return "<label><input type=\"text\" name=\"s_q\" placeholder='&#87;&#104;&#97;&#116;&#32;&#105;&#115;&#32;
              &#$converted[0];&#32;&#43;&#32;&#$converted[1];&#63;'></label>";
  }

    public static function create_captcha1() {?>

          <div class="element-recaptcha"><label class="title"></label>
          <script type="text/javascript">var RecaptchaOptions = {theme : "clean"};</script>
          <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6LfD7O0SAAAAAPjPJ5Mcrmu1egGBmZCl-T4qCkgQ&theme=clean">
          </script><noscript>
          <iframe src="http://www.google.com/recaptcha/api/noscript?k=6LfD7O0SAAAAAPjPJ5Mcrmu1egGBmZCl-T4qCkgQ&hl=en" height="300" width="500" frameborder="0"></iframe></br>
          <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
          <input type="hidden" name="recaptcha_response_field" value="manual_challenge"></noscript>
          <script type="text/javascript">
                if (/#invalidcaptcha$/.test(window.location)) (document.getElementById("recaptcha_widget_div")).className += " error";
          </script>
          </div><?php
    }

    public static function responseCaptcha($post_recaptcha_challenge_field){

        $resp = null;
        if ($_POST["recaptcha_response_field"] !== null) {
            $resp = recaptcha_check_answer (PRIV_KEY,
                                        $_SERVER["REMOTE_ADDR"],
                                        $post_recaptcha_challenge_field,
                                        $_POST["recaptcha_response_field"]);
            if (!$resp->is_valid) {
                 Errors::handle_error2(null,'&#x2718; Please answer the anti-spam question correctly!');
                 exit;
            }
        }
    }

    public static function responseCaptcha2() {

        $recaptcha = $_POST['g-recaptcha-response'];
        if (empty($recaptcha)) {
            Errors::handle_error2(null,'&#x2718; Please re-enter your reCAPTCHA.');
            exit;
        }
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6LfQ4f4SAAAAACdlvVZZAQGTOfzIMU1niKmplJXS'; //google secret key
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url."?secret=".$secret."&response=". $recaptcha . "&remoteip=" . $ip;
        $res = getCurlData($url);
        $res = json_decode($res, true);
        if (!$res['success']) {
            Errors::handle_error2(null,'&#x2718; Please re-enter your reCAPTCHA.');
            exit;
        }else{
            return true;
        }
    }
}
