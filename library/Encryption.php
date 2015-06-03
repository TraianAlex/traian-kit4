<?php
/*
#                              Encryption Class                                #
################################################################################
# Class Name: Encryption                                                       #
# File-Release-Date:  2014/05/06                                               #
#==============================================================================#
# Author: Gabriel Almeida Lopes (deepmaster@hotmail.com.br)
# Facebook: fb.com/gabriel.5592
# Twitter: @GavrilAlmeida
# Copyright © 2014 Gabriel Almeida Lopes  -   All Rights Reserved.
# Thank you - Original Code Class
# OpenCart 1.5.5.1 (encryption.php "class") -> http://www.opencart.com/        #
###############################################################################
*/
/* Licence
 * #############################################################################
 * | This program is free software; you can redistribute it and/or
 * | modify it under the terms of the GNU General var License
 * | as published by the Free Software Foundation; either version 2
 * | of the License, or (at your option) any later version.
 * | This program is distributed in the hope that it will be useful,
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * | GNU General var License for more details.
 * +--------------------------------------------------------------------------
 */

Class Encryption {

	private $key;
	private $iv;
	private $typeEncryption;
	private $separator;

	public function __construct($key =NULL, $type = NULL, $separator=NULL){

            // if don't set all values, return error
            if( $key == NULL or $type == NULL or $separator == NULL ){
                exit('No set all attributes in class Encryption');
            }
            $this->typeEncryption = $type;
            $this->separator = $this->filterSeparator( $separator );

            $key1 = hash( $this->typeEncryption, $key, true);
            $this->key = substr($key1, 0, 32);
            $this->iv = mcrypt_create_iv(32, MCRYPT_RAND);
	}

	public function encode($value) {

            if($value == ''){
                return false;
            }
            $Str = strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,
                    $this->key, $value, MCRYPT_MODE_ECB, $this->iv)),
                    '+/=', '-_,');

            return $this->inverseEncryption($Str);
	}
        
	public function decode($value) {

            if($value == ''){
                return false;
            }
            $Str = $this->reverseEncryption($value);

            return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key,
                    base64_decode(strtr($Str, '-_,', '+/=')),
                    MCRYPT_MODE_ECB, $this->iv));
	}

	private function inverseEncryption($e){

            $replaceA = str_replace(',', '', $e);

            $strrev = strrev($replaceA);

            $substrA = substr($strrev, 0,15);
            $substrB = substr($strrev, 15,15);
            $substrC = substr($strrev, 30);

            $variableMounting = $substrB . $this->separator . $substrC .
                    $this->separator . $substrA;

            return $variableMounting;
	}

	private function reverseEncryption($e){

            $exVariable = explode($this->separator, $e);

            $varA = $exVariable[0];
            $varB = $exVariable[1];
            $varC = $exVariable[2];

            $variableMounting = $varC.$varA.$varB;

            $strrev = strrev($variableMounting);
            return $strrev;
	}

	private function filterSeparator($val = NULL){

            $allowedSeparator = array(
                    '!','@','#','%','&','*','/','+','=','~','?');

            if(in_array($val, $allowedSeparator)):
                    return $val;
            else:
                    return '/';
            endif;
	}

}

//	$secureKey 		= 'SECRET_KEYsdgfsdfsdfsdfsdfsdfsdfsdfsdfsdf';
//# - Encryption algorithm name; Recommended algorithms: sha384, sha512, ripemd256, ripemd320, whirlpool or salsa20 ; http://www.php.net/manual/pt_BR/function.hash.php#104987
//	$typeEncryption = 'sha512';
//# - Separator encrypted string; Allowed: !@#%&*=+/~?
//	$separator 		= '=';
//	$encrypt = new Encryption( $secureKey, $typeEncryption, $separator);
//	$encodeTXT = 'My name is Gabriel Almeida Lopes, I live in Brazil! :-)';
//	$crypt 		= @$encrypt->encode( $encodeTXT );
//	$decrypt 	= @$encrypt->decode( $crypt );
//	print('Encrypted: ' . $crypt);
//	print('<br />');
//	print('Decrypted: ' . $decrypt);

//---------- TEST ENCRYPTION ----------------------------------------------------------------

//	$encrypt = new Encryption( KYC, TYPE, SEPARATOR);
//	$encodeTXT = 'Traian';
//	$crypt 		= $encrypt->encode( $encodeTXT );
//	$decrypt 	= $encrypt->decode( $crypt );
//	print('Encrypted: ' . $crypt);
//	print('<br />');
//	print('Decrypted: ' . $decrypt);

//old
//$aaa = encrypt_data('Traian');
//echo $aaa.'<br>';
//echo decrypt_data($aaa);