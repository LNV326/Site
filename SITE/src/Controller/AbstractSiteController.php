<?php
namespace Controller;

abstract class AbstractSiteController {
	
	protected $_templateName = '';
	protected $_cacheLifetime = 0;
	
	/**
	 * Database EntityManager
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $_em;
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
	
	/**
	 * Template Engine cache setting
	 * 0 - disable, 1 - enable, 2 - custom lifetime
	 */
	protected $_caching = 0;
	protected $_debug = true;
	
	public function __construct($em, $conf, $smarty, $ibforums, $INFO, $std) {
		$this->_em = $em;
		$this->_conf = $conf;
		$this->_smarty = $smarty;
		$this->_ibforums = $ibforums;
		$this->_INFO = $INFO;
		$this->_std = $std;
	}
	
	public function index() {
		$this->_smarty->caching = $this->_caching;
		$this->_smarty->compile_check = $this->_debug;
		$this->_smarty->cache_lifetime = $this->_cacheLifetime;
		// TODO Need to support multy-page caching http://www.smarty.net/docsv2/ru/caching.multiple.caches.tpl 
		if (!$this->_smarty->is_cached($this->_templateName)) {
			$this->getData();
		}
		$this->_smarty->display($this->_templateName);
	}
	
	public function clearCache () {
		$this->_smarty->caching = $this->_caching;
		$this->_smarty->clear_cache($this->_templateName);
	}
	
	protected function getData() {}
}