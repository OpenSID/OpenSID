<?php
/**
 * Simple bcrypt-based password hashing library for php 5.3+ 
 * @package     Bcrypter
 * @version     0.0.1
 * @copyright   Copyright (c) 2017 Suyadi. All Rights Reserved.
 * @license     <https://opensource.org/licenses/MIT> The MIT License (MIT).
 * @author      Suyadi <suyadi.1992@gmail.com>
 */


class Bcrypter {

	const
		// Pesan - pesan error internal
		E_COST='Parameter cost tidak valid',
		E_SALT='Salt setidaknya harus terdiri dari 22 karakter alfa-numerik';

	const
		// Default cost
		COST=10;

	/**
	 * Buat hash (bcrypt) dari sebuah string
	 * @param   string        $pw
	 * @param   string        $salt
	 * @param   integer       $cost
	 * @return  string|FALSE
	 */
	function hash($pw,$salt=NULL,$cost=self::COST) {
		if ($cost<4||$cost>31)
			user_error(self::E_COST,E_USER_ERROR);
		$len=22;
		if ($salt) {
			if (!preg_match('/^[[:alnum:]\.\/]{'.$len.',}$/',$salt))
				user_error(self::E_SALT,E_USER_ERROR);
		}
		else {
			$raw=16;
			$data='';
			if (!$data&&extension_loaded('openssl'))
				$data=openssl_random_pseudo_bytes($raw);
			if (!$data)
				for ($i=0;$i<$raw;$i++)
					$data.=chr(mt_rand(0,255));
			$salt=str_replace('+','.',base64_encode($data));
		}
		$salt=substr($salt,0,$len);
		$hash=crypt($pw,sprintf('$2y$%02d$',$cost).$salt);
		return strlen($hash)>13?$hash:FALSE;
	}

	/**
	 * Cek apakah password masih cukup aman atau tidak
	 * @param   string    $hash
	 * @param   integer   $cost
	 * @return  boolean
	 */
	function needs_rehash($hash,$cost=self::COST) {
		list($list)=sscanf($hash,"$2y$%d$");
		return $list<$cost;
	}

	/**
	 * Verifikasi string password terhadap hashnya
	 * @param   string   $pw
	 * @param   string   $hash
	 * @return  boolean
	 */
	function verify($pw,$hash) {
		$val=crypt($pw,$hash);
		$len=strlen($val);
		if ($len!=strlen($hash)||$len<14)
			return FALSE;
		$out=0;
		for ($i=0;$i<$len;$i++)
			$out|=(ord($val[$i])^ord($hash[$i]));
		return $out===0;
	}

}