<?php

namespace Controller;

use Template\TemplateEngineAdapter;
use Utils\Debug;
abstract class AbstractSiteController {
		
	protected $_templateName = null;
	protected $_templateParams = null;
	protected $_cacheLifetime = 0;
	
	/**
	 * Database EntityManager
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $_em;
	/**
	 * Database Driver 
	 * @var unknown
	 * @deprecated
	 */
	protected $_DB;
	/**
	 * Global configuration
	 * @var Array
	 */
	protected $_conf;

	protected $_ibforums;
	protected $_INFO;
	protected $_std;
	protected $_nfs;
	protected $_sdk_info;
	protected $_style_id;
	protected $_lang;
	protected $_SDK;
	protected $_admin;
	
	/**
	 * Template Engine cache setting
	 * 0 - disable, 1 - enable, 2 - custom lifetime, -1 - forever
	 */
	protected $_caching = 0;
	protected $_debug = false;
	
	public function __construct($em, $DB, $conf, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin) {
		$this->_em = $em;
		$this->_DB = $DB;
		$this->_conf = $conf;
		$this->_ibforums = $ibforums;
		$this->_INFO = $INFO;
		$this->_std = $std;
		$this->_nfs = $nfs;
		$this->_sdk_info = $sdk_info;
		$this->_style_id = $style_id;
		$this->_lang = $lang;
		$this->_SDK = $SDK;
		$this->_admin = $admin;
	}
	
	/**
	 * @deprecated
	 */
	public function index() {
			
		// Get the template engine adapter
		$templateEngine = TemplateEngineAdapter::getInstanceBase($this->_templateName);
		// Check reault cache status (if result cache is out of current interests then get data from database)
		if ( !$templateEngine->isCached($this->_templateName, null, $this->_caching, $this->_cacheLifetime) ) {
			$this->_templateParams = $this->getData();
		}
		$templateEngine->setCachingMode($this->_caching);
		$templateEngine->setCacheLifetime($this->_cacheLifetime);
		$templateEngine->display( $this->_templateName, $this->_templateParams );
	}	
	
	public function renderTemplate($templateName, $templateDataSourceObject, $templateDataFuncName, $resultCacheLifetime, $cacheId = null) {
		$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
		// Try to get cached result for the template
		// 		$templateEngine->setCachingMode(TemplateEngineAdapter::CACHE_MODE_DEFAULT_LIFETIME);
		$templateEngine->setCacheLifetime($resultCacheLifetime);
		$cachedResult = $templateEngine->getCachedResult($templateName, $cacheId);
		if ($cachedResult != false)
			return $cachedResult;
		// Set the fiture cache lifetime and render it
		$templateEngine->setCacheLifetime($resultCacheLifetime);
		return $templateEngine->render( $templateName, (is_object($templateDataSourceObject)) ? $templateDataSourceObject->$templateDataFuncName() : null, $cacheId );
	}
	
// 	public function renderTemplate($templateName, $templateDataFuncName, $resultCacheLifetime, $cacheId = null) {
// 		$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);		
// 		// Try to get cached result for the template
// // 		$templateEngine->setCachingMode(TemplateEngineAdapter::CACHE_MODE_DEFAULT_LIFETIME);
// 		$templateEngine->setCacheLifetime($resultCacheLifetime);
// 		$cachedResult = $templateEngine->getCachedResult($templateName, $cacheId);
// 		if ($cachedResult != false)
// 			return $cachedResult;
// 		// Set the fiture cache lifetime and render it
// 		$templateEngine->setCacheLifetime($resultCacheLifetime);
// 		return $templateEngine->render( $templateName, (!is_null($templateDataFuncName)) ? $templateDataFuncName() : null, $cacheId );
// 	}
	
	/**
	 * @deprecated
	 */
	protected abstract function getData();
	
}