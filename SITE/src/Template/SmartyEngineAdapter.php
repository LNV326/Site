<?php

namespace Template;

use Template\TemplateEngineInterface;
use Utils\Debug;
use Utils\SingletonInterface;

final class SmartyEngineAdapter extends TemplateEngineAdapter implements TemplateEngineInterface, SingletonInterface {
	
	/* ====================== */
	/* ======= STATIC ======= */
	
	/**
	 * An instance of class
	 * @var SingletonInterface
	 */
	private static $_instance;
	/**
	 * An instance of Smarty template engine
	 * @see http://www.smarty.net/
	 * @var Smarty
	 */
	private static $_engine;
	
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
	
	/**
	 * Template Engine cache setting
	 * 0 - disable, 1 - enable, 2 - custom lifetime, -1 - forever
	 */
	const _CACHING_RESULT_ = 1;
	const _COMPILE_CHECK = false;
	
	protected function __construct() {
		global $smarty;
		parent::__construct();
		self::$_engine = $smarty;
		self::$_engine->caching = self::_CACHING_RESULT_;
// 		self::$_engine->setCompileCheck(self::_COMPILE_CHECK);
	}
		
	
	public function isCached( $templateName, $cacheId = null, $cachingMode = self::CACHE_MODE_DISABLE, $resultCacheLifetime = 0 ) {
		self::$_engine->caching = $cachingMode;
		self::$_engine->cache_lifetime = $resultCacheLifetime;
		return self::$_engine->is_cached( $templateName, $cacheId );
	}
	
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineAdapter::renderExecute()
	 */
	protected function renderExecute( $templateName, $templateParams = null, $cacheId = null ) {
		// Assign params for template
		if ( is_array($templateParams) )
			self::$_engine->assign($templateParams);
		$result = self::$_engine->fetch( $templateName, $cacheId );
		// Unsen all assigned params
		if ( is_array($templateParams) )
			self::$_engine->clear_assign( array_keys($templateParams) );
		return $result;
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::getCachingMode()
	 */
	public function getCachingMode() {
		return self::$_engine->caching;
	}
		
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::setCachingMode()
	 */
	public function setCachingMode($cachingMode) {
		if ( is_numeric($cachingMode) && $cachingMode >= 0 ) {
			self::$_engine->caching = $cachingMode;
			if ($cachingMode == self::CACHE_MODE_DEFAULT)
				self::$_engine->cache_lifetime = self::TIME_EXPIRE_DEFAULT;
		} else {
			self::$_engine->caching = self::CACHE_MODE_DISABLE;
			self::$_engine->cache_lifetime = 0;
		}
	}

	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::getCacheLifetime()
	 */
	public function getCacheLifetime() {
		return self::$_engine->cache_lifetime;
	}
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::setCacheLifetime()
	 */
	public function setCacheLifetime($resultCacheLifetime) {
		if ( is_numeric($resultCacheLifetime) && $resultCacheLifetime > 0 ) {
			self::$_engine->caching = self::CACHE_MODE_INDIVIDUAL_LIFETIME;
			self::$_engine->cache_lifetime = $resultCacheLifetime;
		} else {
			self::$_engine->caching = self::CACHE_MODE_DISABLE;
			self::$_engine->cache_lifetime = 0;
		}
			
	}		
	
	/* (non-PHPdoc)
	 * @see \Template\TemplateEngineInterface::getCachedResult()
	 */
	public function getCachedResultExecute($templateName, $cacheId = null) {
		if ( self::$_engine->is_cached( $templateName, $cacheId ) )
			return self::$_engine->fetch( $templateName, $cacheId );
		return false;
	}

	
}