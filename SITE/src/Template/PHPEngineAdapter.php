<?php

namespace Template;

use Template\TemplateEngineInterface;
use Utils\Debug;

final class PHPEngineAdapter extends TemplateEngineAdapter {
	
	private static $_instance;
	private $_nfs;
	private $_std;
	
	const _PURE_PHP_TEMPLATE_PATH_ = '/../Template/1/Controller/';
	
	protected function __construct() {
		global $nfs, $std;
		parent::__construct();
		$this->_nfs = $nfs;
		$this->_std = $std;
	}
	
	public static function getInstance() {
		if ( is_null(self::$_instance) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function isCached( $templateName, $cacheId, $cacheResult = 0, $resultCacheLifetime = 0 ) {
		return false;
	}
		
	protected function renderExecute( $templateName, $templateParams = array(), $cacheId = null ) {
		global $style_id, $lil, $line;
		ob_start();
		$out = $templateParams;
		include realpath(__DIR__.self::_PURE_PHP_TEMPLATE_PATH_.$templateName);
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
	
}