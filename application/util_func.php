<?php

    function get_url(){
        
        $raw_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $arr1 = explode('/', trim(str_replace(SITE_ROOT, '', parse_url($raw_url)['path']), '/'));
        foreach ($arr1 as $item):
            $url[] = isset($item) ? $item : '';
        endforeach;
        (new Validate)->get_sec2($url);
        return $url;
    }

    function show_date() {
        date_default_timezone_set("America/New_York");
        echo '<div style="float:right">' . date("Y/m/d H:i:s") . '</div><br>';
    }
/**
 * show_error
 * @param type $message
 */
    function debug_print($message) {
        if (DEBUG_MODE)
            echo $message;
    }
/**
 * set date time zone in Rss
 */
    function setDateTime($item) {

        $date = new DateTime($item->pubDate);
        $date->setTimezone(new DateTimeZone('America/Toronto'));
        $offset = $date->getOffset();
        $timezone = ($offset == -14400) ? ' EDT' : ' EST';
        echo $date->format('M j, Y, g:ia') . $timezone;
    }

/**
 * footer
 */
    function setDate(){
        date_default_timezone_set("America/Toronto");
        return 2013 == date('y') ? 2013 : "2013 &#8211; ".date('y');
    }

    function count_page1(){

        $cfile = "inc/lctr-test.php.dat";
        $fh = @fopen($cfile, "r+") or die("<BR>Failed to open file <I>$cfile</I>.");
        @flock($fh, LOCK_EX) or die("<BR>Could not lock file <I>$cfile</I>.");
        $s = @fgets($fh, 6);
        $count = (int) $s + 1;
        $count = str_pad($count, 6);
        @rewind($fh) or die("<BR>Failed to rewind file <I>$cfile</I>.");
        if (@fwrite($fh, $count) == -1)
            die("<BR>Failed to write to file <I>$cfile</I>.");
        echo "$count";
        @flock($fh, LOCK_UN) or die("<BR>Could not unlock file <I>$cfile</I>.");
        fclose($fh) or die("<BR>Failed to close file <I>$cfile</I>.");

        echo "</b> times.<br>";//brought from index
    }

    function count_page2(){

        $counterFile = "inc/count.dat";
        if (!file_exists( $counterFile )) {
            if (!( $handle = fopen( $counterFile, "w" ))) {
                    die( "Cannot create the counter file." );
            } else {
                    fwrite( $handle, 0 );
                    fclose( $handle );
            }
        }
        if (!( $handle = fopen( $counterFile, "r" )))
            die("Cannot read the counter file.");
        $counter = (int) fread( $handle, 20 );
        fclose( $handle );
        $counter++;
        echo "<p>You're visitor No. $counter.</p>";
        if (!( $handle = fopen( $counterFile, "w" )))
            die( "Cannot open the counter file for writing." );
        fwrite( $handle, $counter );
        fclose( $handle );
    }
    
    function setVerb($view) {

        if ($view == $_SESSION['user']){
            $name1 = $name2 = "Your";
            $name3 =         "You are";
        }else{
            $name1 ="<a href='".SITE_ROOT."/members/{$view}'>{$view}</a>'s";
            $name2 = $view."'s";
            $name3 = $view." is";
        }
        return [$name1, $name2, $name3];
    }
    
    function ProtectEmail($email){

        $t1 = strpos($email, '@');
        $t2 = strpos($email, '.', $t1);
        if (!$t1 || !$t2) return FALSE;

        $e1 = substr($email, 0, $t1);
        $e2 = substr($email, $t1, $t2 - $t1);
        $e3 = substr($email, $t2);

        return "<script>e1='$e1';e2='$e2';e3='$e3';document.write" .
               "('<a href=\'mailto:' + e1 + e2 + e3 + '\'>' + e1 " .
               "+ e2 + e3 + '</a>');</script>";
     }
/*
 * delete the menu if I need to change it, made with class menu and item
 */    
     function delete_menu(){

        $items = new DirectoryIterator($_SERVER['DOCUMENT_ROOT'] . SITE_ROOT . "/tmp/");
        $files = new RegexIterator($items, '/\.(?:cache|info|tmp)$/i'); 

        foreach ($files as $file){
            unlink($_SERVER['DOCUMENT_ROOT'] . SITE_ROOT . "/tmp/" . $file);
        }
        URL::to(SITE_ROOT."/php/menu");
    }
/**
 * encrypt and decrypt data
 * @param type $message
 * @return string
 */
    function encrypt_data($message){//fix
       return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KY, $message,MCRYPT_MODE_ECB,IV);
    }
    
    function decrypt_data($enc){
       return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KY, $enc, MCRYPT_MODE_ECB, IV);
    }
/**
 * combine a token from multiple key
 * @param type $key1
 * @param type $key2
 * @param type $func
 * @return string
 */
    function hashfinal($key1, $key2=0, $func='sha512') {
        
        $ctz = null;
        $ctz = hash_init($func);
        hash_update($ctz, $key1);
        hash_update($ctz, $key2);
        return hash_final($ctz);
    }
/**
 * for passwords one way encryption
 * @param type $pass
 * @return string
 */
    function hash_pass($pass){
        return bin2hex(mhash(MHASH_GOST, $pass));
    }

    function hash_pass2($pass){
        return bin2hex(mhash(MHASH_TIGER, $pass));
    }

    function hash_pass3($pass){
        return bin2hex(mhash(MHASH_RIPEMD160, $pass));
    }
//hash("sha256", 4444);
//hash("sha512", '4444'.'dfsdf'.'sdfsdf');


//set_exception_handler('handleMissedException');
//param 1 by mail, 3 write in the error log
//error_log("Houston, we've had a problem.", 1, "victor_traian@yahoo.com", "Cc: victor_traian@hotmail.com" );
    
    function getCurlData($url){
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        curl_close($curl);
        return $curlData;
    }
    
    function EmbedYouTubeVideo($id, $width, $height, $hq, $full, $auto){
   // This plug-in accepts the absolute URL of a web page
   // and a link featured within that page. The link is then
   // turned into an absolute URL which can be independently
   // accessed. Only applies to http:// URLs. The arguments
   // required are:
   //    $id:     The YouTube video id
   //    $width:  The display width
   //    $height: The display height
   //             Standard YouTube video is 425 x 344 pixels
   //    $hq:     'true' or 1 for high quality
   //    $full:   'true' if full screen zoom allowed
   //    $auto:   1 to auto start, 0 for no

   if ($hq) $q = "&ap=%2526fmt%3D18";
   else $q = "";

   return <<<_END
<object width="$width" height="$height">
<param name="movie"
   value="http://www.youtube.com/v/$id&fs=1&autoplay=$auto$q">
</param>
<param name="allowFullScreen" value="$full"></param>
<param name="allowscriptaccess" value="always"></param>
<embed src="http://www.youtube.com/v/$id&fs=1&autoplay=$auto$q"
   type="application/x-shockwave-flash"
   allowscriptaccess="always" allowfullscreen="$full"
   width="$width" height="$height"></embed></object>
_END;
}