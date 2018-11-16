<?php
namespace App\Libraries;

/**
 * 数据处理类
 */
class Data{
	// CRYPTO_CIPHER_BLOCK_SIZE 32
	private static $_secret_key = 'dslr-userinfo-aeckey';
	private static $default = 'aes';//默认加密方法
	private static $token_keys = 'dslr-tokenkeys-20160715';
	/**
	 * 加密
	 */
	public static function md5($code='') {
		if(empty($code)) return '';
		$code = md5(md5(self::$_secret_key).$code.md5(self::$_secret_key));
		return md5($code);
	}
	/**
	 * 加密
	 */
	public static function sha1($code='') {
		if(empty($code)) return '';
		$code = sha1(sha1(self::$_secret_key).$code.sha1(self::$_secret_key));
		return sha1($code);
	}
	/**
	 * 可变加密
	 */
	public static function des($key='',$data='',$xcrypt=false) {
		if(empty($key) || empty($data)) return '';
		$key = self::md5(self::sha1($key));
		if($xcrypt){//解密
			$data = base64_decode($data);
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
			$iv = mb_substr($data,0,32,'latin1');
			mcrypt_generic_init($td,$key,$iv);
			$data = mb_substr($data,32,mb_strlen($data,'latin1'),'latin1');
			$data = mdecrypt_generic($td,$data);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			return trim($data);
		}else{
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
			$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
			mcrypt_generic_init($td,$key,$iv);
			$encrypted = mcrypt_generic($td,$data);
			mcrypt_generic_deinit($td);
			return base64_encode($iv . $encrypted);
		}
	}
	/**
	 * AES加解密
	 */
	public static function aes($key='',$data='',$xcrypt=false) {
		if(empty($key) || empty($data)) return '';
		$key = self::md5(self::sha1($key));
		if($xcrypt){//解密
			$data = base64_decode($data);
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB),MCRYPT_RAND);
			$encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv);
			$encrypt_str = trim($encrypt_str);
			$encrypt_str = self::stripPKSC7Padding($encrypt_str);
			return $encrypt_str;
		}else{
			$data = trim($data);
			$data = self::addPKCS7Padding($data);
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB),MCRYPT_RAND);
			$encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv);
			return base64_encode($encrypt_str);
		}
	}
	/**
	 * AES加解密
	 */
	public static function base64($key='',$data='',$xcrypt=false) {
		if(empty($key) || empty($data)) return '';
		$key = self::md5(self::sha1($key));
		if($xcrypt){//解密
			$data    = $data;
			$data    = base64_decode($data);
			$len    = strlen($key);
			$code   = '';$t_len = strlen($data);
			for($i=0; $i<$t_len; $i++){
				$k  = $i % $len;
				$code  .= $data[$i] ^ $key[$k];
			}
		}else{//加密
			$data    = (string)$data;
			$len    = strlen($key);
			$code   = '';$t_len = strlen($data);
			for($i=0; $i<$t_len; $i++){
				$k  = $i % $len;
				$code  .= $data[$i] ^ $key[$k];
			}
			$code = base64_encode($code);
		}
		return $code;
	}
	/**
	 * 加密
	 */
	public static function encode($key='',$data='',$deft='')
	{
		if(empty($deft)) $deft = self::$default;
		if('aes' == $deft){
			return self::aes($key,$data);
		}else{
			return self::base64($key,$data);
		}
	}
	/**
	 * 解密
	 */
	public static function decode($key='',$data='',$deft='')
	{
		if(empty($deft)) $deft = self::$default;
		if('aes' == $deft){
			return self::aes($key,$data,true);
		}else{
			return self::base64($key,$data,true);
		}
	}
	/**
	 * 填充算法
	 * @param string $source
	 * @return string
	 */
	private static function addPKCS7Padding($source){
		$source = trim($source);
		$block = mcrypt_get_block_size('rijndael-256', 'ecb');
		$pad = $block - (strlen($source) % $block);
		if ($pad <= $block) {
			$char = chr($pad);
			$source .= str_repeat($char, $pad);
		}
		return $source;
	}
	/**
	 * 移去填充算法
	 * @param string $source
	 * @return string
	 */
	private static function stripPKSC7Padding($source){
		$source = trim($source);
		$char = substr($source, -1);
		$num = ord($char);
		if($num==62)return $source;
		$source = substr($source,0,-$num);
		return $source;
	}





}
?>
