<?php

namespace Template;

use Template\TemplateEngineInterface;
use Utils\Debug;

final class SmartyEngineAdapter extends TemplateEngineAdapter {
	
	private static $_instance;
	private static $_engine;
	
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
	
	public static function getInstance() {
		if ( is_null(self::$_instance) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function isCached( $templateName, $cacheId = null, $cacheResult = 0, $resultCacheLifetime = 0 ) {
		self::$_engine->caching = $cacheResult;
		self::$_engine->cache_lifetime = $resultCacheLifetime;
		return self::$_engine->is_cached( $templateName, $cacheId );
	}
		
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
	
	protected function getCachingMode() {
		return self::$_engine->caching;
	}
	
	protected function getCacheLifetime() {
		return self::$_engine->cache_lifetime;
	}
	
}