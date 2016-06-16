<?php

namespace Template;

use Template\TemplateEngineInterface;
use Utils\Debug;
use Utils\SingletonInterface;

final class PHPEngineAdapter extends TemplateEngineAdapter implements TemplateEngineInterface, SingletonInterface {
	
	/* ====================== */
	/* ======= STATIC ======= */
	
	/**
	 * An instance of class
	 * @var SingletonInterface
	 */
	private static $_instance;
	
	/* (non-PHPdoc)
	 * @see \Utils\SingletonInterface::getInstance()
	 */
	public static function getInstance() {
		if ( is_null(self::$_instance) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	/* (non-PHPdoc)
	 * @see \Utils\SingletonInterface::hasInstance()
	 */
	public static function hasInstance() {
		return ( is_null(self::$_instance) ) ? false : true;
	}
	
	/* ======================= */
	/* ======= DYNAMIC ======= */
	
	private $_nfs;
	private $_std;
	
	const _PURE_PHP_TEMPLATE_PATH_ = '/../Template/1/Controller/';
	
	protected function __construct() {
		global $nfs, $std;
		parent::__construct();
		$this->_nfs = $nfs;
		$this->_std = $std;
	}
	
	public function isCached( $templateName, $cacheId, $cachingMode = self::CACHE_MODE_DISABLE, $resultCacheLifetime = 0 ) {
		return false;
	}
		
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineAdapter::renderExecute()
	 */
	protected function renderExecute( $templateName, $templateParams = null, $cacheId = null ) {
		global $style_id, $lil, $line, $SDK, $sape_show, $empty, $sape, $conf, $nfs, $lang, $std;		
		// Assign params for template
		if ( is_array($templateParams) )
			$out = $templateParams;
		ob_start();
		include realpath(__DIR__.self::_PURE_PHP_TEMPLATE_PATH_.$templateName);
		$result = ob_get_contents();
		ob_end_clean();
		// Unsen all assigned params
		if ( is_array($templateParams) )
			unset($out);
		return $result;
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::getCachingMode()
	 */
	public function getCachingMode() {
		return self::CACHE_MODE_DISABLE;
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::setCachingMode()
	 */
	public function setCachingMode($cachingMode) {
		// Do nothing
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::getCacheLifetime()
	 */
	public function getCacheLifetime() {
		return self::CACHE_MODE_DISABLE;
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::setCacheLifetime()
	 */
	public function setCacheLifetime($resultCacheLifetime) {
		// Do nothing			
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::getCachedResult()
	 */
	public function getCachedResultExecute($templateName, $cacheId = null) {
		return false;
	}
	
}