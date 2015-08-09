<?php

namespace Utils;

/**
 * Check query string parameters and redirect queries to another domain. Properties are hardcoded.
 * 
 * @author Nikolay Lukyanov
 * 
 * @version 1.0 Tested 19/08/2015
 *
 */
final class RedirectService {
	
	private $_nfs;
	private static $_Domains = array(
			'files' => 'http://files.nfsko.ru/index.php',
			'gallery' => 'http://images.nfsko.ru/index.php'
	);
	
	public function __construct($nfs) {
		$this->_nfs = $nfs;
	}
	
	public function check() {
		if (array_key_exists($this->_nfs->input['page'], self::$_Domains)) {
			$newDomain = self::$_Domains[$this->_nfs->input['page']];
			$newUrl = $newDomain.'?'.$_SERVER['QUERY_STRING'];
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$newUrl);
			exit;
		}
	}
}