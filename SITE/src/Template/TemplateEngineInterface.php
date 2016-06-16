<?php

namespace Template;

interface TemplateEngineInterface {
	
	const CACHE_MODE_DISABLE = 0;
	const CACHE_MODE_DEFAULT_LIFETIME = 1;
	const CACHE_MODE_INDIVIDUAL_LIFETIME = 2;
	const CACHE_MODE_DEFAULT = 1; //self::CACHE_MODE_DEFAULT_LIFETIME;
	
	const TIME_EXPIRE_0 = 0;
	const TIME_EXPIRE_10_SEC = 10;
	const TIME_EXPIRE_1_MIN = 60;
	const TIME_EXPIRE_1_HOUR = 3600; 
	const TIME_EXPIRE_1_DAY = 86400;
	const TIME_EXPIRE_DEFAULT = 3600; //self::TIME_EXPIRE_1_HOUR;
	
	/**
	 * Render and return the template with given template name and params.
	 * If render result stores is caching and it is actual then cached result shoult be returned.
	 * @param string $templateName
	 * @param array $templateParams
	 * @param string $cacheId
	 */
	public function render($templateName, $templateParams, $cacheId);
	/**
	 * Render and display the template with given template name and params
	 * @param string $templateName
	 * @param array $templateParams
	 * @param string $cacheId
	 */
	public function display($templateName, $templateParams, $cacheId);
	/**
	 * Return TRUE if the result of rendering template with given name stores in cache and it is actual
	 * @param string $templateName
	 * @param string $cacheId
	 * @param integer $cachingMode
	 * @param integer $resultCacheLifetime
	 */
	public function isCached($templateName, $cacheId, $cachingMode, $resultCacheLifetime );
	
	public function getCacheLifetime();
	public function setCacheLifetime($resultCacheLifetime);
	
	public function getCachingMode();
	public function setCachingMode($cachingMode);
	
	public function getCachedResult($templateName, $cacheId);
	
	/**
	 * Delete all result cache
	 */
	public function clearAllCache();
}