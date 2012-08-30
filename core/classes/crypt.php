<?php !defined('IN_APP') and header('location: /');

class Crypt {
	
	public static function init() {

	}
	
	/** 
	 *	@desc Encode some data
	 *  @param Data
	 *  @return Boolean / Data (encoded)
	 */
	public static function encode($data, $salt = '', $type = '') {
	    if(empty($type)) $type = Config::get('crypt.encode_method');
	   	if(empty($salt)) $salt = Config::get('crypt.salt');
	   	
	    return self::doMethod($type, 'encode', $data . $salt);	
	}
	
	/** 
	 *	@desc Decode some data
	 *  @param Data (encoded)
	 *  @return Boolean / Data (decoded)
	 */
	public static function decode($data, $salt = '', $type = '') {
	    if(empty($type)) $type = Config::get('crypt.encode_method');
	   	if(empty($salt)) $salt = Config::get('crypt.salt');
	   	
	    return self::doMethod($type, 'decode', $data . $salt);	
	}
	
	/** 
	 *	@desc Encrypt some data
	 *  @param Data
	 *  @return Boolean / Data (encoded)
	 */
	public static function encrypt($data, $salt = '', $type = '') {
	    if(empty($type)) $type = Config::get('crypt.encrypt_method');
	   	if(empty($salt)) $salt = Config::get('crypt.salt');
	   	
	    return self::doMethod($type, 'encrypt', $data . $salt);	
	}
	
	/** 
	 *	@desc Encoding, Decoding and Encryption methods
	 *  @param Method name
	 *  @param Type of method
	 *  @param Parameter
	 *  @return String
	 */
	private static function doMethod($name, $type, $param) {
		
		$methods = array(
			'encode' => array(
				'base64' => create_function('$stuff', 'return base64_encode($stuff);'),
				'rot13' => create_function('$stuff', 'return str_rot13($stuff);')
			),
			
			'decode' => array(
				'base64' => create_function('$stuff', 'return base64_decode($stuff);'),
				'rot13' => create_function('$stuff', 'return str_rot13($stuff);'),
			),
			
			'encrypt' => array(
				'des' => create_function('$stuff', 'return crypt($stuff);'),
				'md5' => create_function('$stuff', 'return md5($stuff);'),
				'sha1' => create_function('$stuff', 'return sha1($stuff);'),
				'sha256' => create_function('$stuff', 'return hash("sha256", $stuff);'),
				'sha512' => create_function('$stuff', 'return hash("sha512", $stuff);'),
				'blowfish ' => create_function('$stuff', 'return hash("blowfish", $stuff);'),
				'whirlpool' => create_function('$stuff', 'return hash("whirlpool", $stuff);')
			)
		);
		

		$func = $methods[strtolower($type)][strtolower($name)];
		
		return $func($param);
		
	}
}	