<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;

class HashService extends BaseService{
	
	//sanitize from the symbols used in URL
	private $search = array('&', '?', ' ', '+', '/', '=');
	private $replace = array('E', 'Q', 'z', 'j', 'R', '3');
	private $saltStatic;
	private $saltInternal;
	private $saltExternal;
	
	public function __construct() 
	{
		$this->saltStatic = Config::get('custom.encSaltStatic');
		$this->saltInternal = Config::get('custom.encSaltInternal');
		$this->saltExternal = Config::get('custom.encSaltExternal');
	}

	//SSHA LDAP password generation algorithm for standard PHP > 5.0 
	public function hashSSHA($password) {
		$salt = sha1(rand());
		$salt = substr($salt, 0, 4);
		$hash = base64_encode(sha1($password . $salt, true) . $salt);

		//sanitize from the symbols used in URL
		$hash = str_replace($this->search, $this->replace, $hash);

		return $hash;
	}

	public function hashSSHA_StaticSalt($password) {
		$salt = $this->saltStatic;
		$hash = base64_encode(sha1($password . $salt, true) . $salt);

		//sanitize from the symbols used in URL
		$hash = str_replace($this->search, $this->replace, $hash);

		return $hash;
	}

	public function generateSignature($password, $salt_external) {
		$salt_internal = $this->saltInternal;
		$salt = $salt_internal . $salt_external;
		//$hash = base64_encode( sha1($password . $salt, true) . $salt ); 
		//we removed the true from sha1, because the same function GenerateSignature is used and in C# service, but in C# there is no possibility for sha1 with true
		$hash = base64_encode(sha1($password . $salt) . $salt);

		$hash = str_replace($this->search, $this->replace, $hash);

		return $hash;
	}
	
	private function timestampHashed()
	{
		$timestamp_time = time();
		$timestamp_time_some_time_ago = $timestamp_time - 178030;  //very important defence - because it would be difficult to understand the hashing algorithm with random time
		
		return $this->hashSSHA($timestamp_time_some_time_ago);
	}
	
	public function signature(&$timestampHashed)
	{
		$timestampHashed = $this->timestampHashed();
		return $this->generateSignature($timestampHashed, $this->saltExternal);
	}
	
	/**
	 * Check signature that is generated on another server
	 * @param string $signiture
	 * @param string $timestampHashed
	 * @return bool
	 */
	public function checkSigniture($signiture, $timestampHashed)
	{
		$controlSigniture = $this->generateSignature($timestampHashed, $this->saltExternal);
		if($controlSigniture === $signiture) {
			return true;
		}
		return false;
	}

}
