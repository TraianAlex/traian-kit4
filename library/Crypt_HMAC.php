<?php
/**
* @package php-pdo
* crypt-hmac.php
* Crypt_HMAC class file
* Author: Traian Alexandru <victor_traian@yahoo.com>
* @version 1.0 2014-01-07
* @copyright Copyright (c) 2014, All Rights Reserved
* @license GNU General Public License
* @since Since Release 1.0
*/
class Crypt_HMAC {

    private $_func;
    private $_data = 0;
    private $_ret = array();

//* Pass method as first parameter
//* @param string method - Hash function used for the calculation @return void @access public*/
    public function __construct($key, $method = 'sha1'){
        
        if (!in_array($method, array('md5', 'sha1'))) {
            die("Unsupported hash function '$method'.");
        }
        $this->_func = $method;
        /* Pad the key as the RFC wishes (step 1) */
        if (strlen($key) > 64) {
            $key = pack('H32', $method($key));
        }
        if (strlen($key) < 64) {
            $key = str_pad($key, 64, chr(0));
        }
      /* Calculate the padded keys and save them (step 2 & 3) */
            $this->_ipad = substr($key, 0, 64) ^ str_repeat(chr(0x36),64);
            $this->_opad = substr($key, 0, 64) ^ str_repeat(chr(0x5C),64);
    }
/**Hashing function
* @param string data - string that will hashed (step 4) @return string @access public*/
    private function hash($data){
        
        $func = $this->_func;
        $inner = pack('H32', $func($this->_ipad . $data));
        $digest = $func($this->_opad . $inner);
        return $digest;
    }

    public function create_parameters($array) {

        $this->_data = 0;
        $this->_ret = array();
        /* Construct the string with our key/value pairs */
        foreach ($array as $key => $value) {
            $this->_data .= $value;
            $this->_ret[] = $value;
        }
        $hash = $this->hash($this->_data);
        $this->_ret[] = $hash;
        return join('/', $this->_ret);//return join ('&amp;', $ret);
    }
//To verify the parameters passed to the script, we can use this script:
    public function verify_parameters($array) {
/* Store the hash in a separate variable and unset the hash from the array itself (as it was not used in constructing the hash */
        $hash = $array['h'];
        unset($array['h']);
        /* Construct the string with our key/value pairs */
        foreach ($array as $key => $value) {
            $this->_data .= $key . $value;
            $this->_ret[] = "$key=$value";
        }
        if ($hash != $this->hash($this->_data)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

/* The RFC recommends a key size larger than the output hash for the hash function you use (16 for md5() and 20 for sha1()). */
//define('KEY', 'iwpmsjtpsgfhjdsgfjhdfgdjshgfkdjhflsjkfgdjhgfdjhgfjdhgfdksfu');

//$h = new Crypt_HMAC(KEY);