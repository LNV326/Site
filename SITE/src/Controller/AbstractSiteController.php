<?php
namespace Controller;

abstract class AbstractSiteController {
	
	protected $_templateName = null;
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
	/**
	 * Template Engine
	 */
	protected $_smarty;
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
	protected $_debug = true;
	
	public function __construct($em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin) {
		$this->_em = $em;
		$this->_DB = $DB;
		$this->_conf = $conf;
		$this->_smarty = $smarty;
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
	
	public function index() {
		$Debug = new \Debug;
		if ( is_null($this->_templateName) ) {
			$Debug->startTimer();
			$this->getData();
			$getDataTime = $Debug->endTimer();
		} else {
			$this->_smarty->caching = $this->_caching;
			$this->_smarty->compile_check = $this->_debug;
			$this->_smarty->cache_lifetime = $this->_cacheLifetime;
			// TODO Need to support multy-page caching http://www.smarty.net/docsv2/ru/caching.multiple.caches.tpl
			if (! $this->_smarty->is_cached( $this->_templateName )) {
				$Debug->startTimer();
				$this->getData();
				$getDataTime = $Debug->endTimer();
			}
			$Debug->startTimer();
			$this->_smarty->display( $this->_templateName );
			$renderTime = $Debug->endTimer();
		}
		if (isset( $getDataTime ))
			echo 'GetData time: ' . $getDataTime . '<br>';
		if (isset( $renderTime ))
			echo 'Render time: ' . $renderTime . '<br>';
		$this->postIndexHook();
	}
	
	public function clearCache () {
		$this->_smarty->caching = $this->_caching;
		$this->_smarty->clear_cache($this->_templateName);
	}
	
	protected function getData() {}
	protected function postIndexHook() {}
}