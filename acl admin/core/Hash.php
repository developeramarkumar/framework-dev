<?php
namespace Core;
use Illuminate\Encryption\Encrypter;
use \Config;
class Hash {
	private $key = '';
   	public static function encrypt($value) {
		$encrypter = new Encrypter(Config::get('app.key'),Config::get('app.cipher'));
		return $encrypter->encrypt($value);
	}
	public static function decrypt($value){
		$encrypter = new Encrypter(Config::get('app.key'),Config::get('app.cipher'));
		return $encrypter->decrypt($value);
	}
	// public static function encrypt($pure_string) {
	//     $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	//     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	//     $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, '!@#$%^&*', utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
	//     return $encrypted_string;
	// }

	
	// public static function  decrypt($encrypted_string) {
	//     $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	//     $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	//     $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, '!@#$%^&*', $encrypted_string, MCRYPT_MODE_ECB, $iv);
	//     return $decrypted_string;
	// }
}