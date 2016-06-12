<?php

namespace Template;

interface TemplateEngineInterface {
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
	 * @param integer $cacheResult
	 * @param integer $resultCacheLifetime
	 */
	public function isCached($templateName, $cacheId, $cacheResult, $resultCacheLifetime );
	/**
	 * Delete all result cache
	 */
	public function clearAllCache();
}