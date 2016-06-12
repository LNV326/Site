<?php

namespace Controller;

use Template\TemplateEngineAdapter;
use Utils\Debug;
abstract class AbstractSiteController {
		
	protected $_templateName = null;
	protected $_templateParams = array();
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
	
	public function index() {
			
		// Get the template engine adapter
		$templateEngine = TemplateEngineAdapter::getInstanceBase($this->_templateName);
		// Check reault cache status (if result cache is out of current interests then get data from database)
		if ( !$templateEngine->isCached($this->_templateName, null, $this->_caching, $this->_cacheLifetime) ) {
			$Debug = new Debug;
			$Debug->startTimer();
			$this->_templateParams = $this->getData();
			$getDataTime = $Debug->endTimer();
		}
		$templateEngine->display( $this->_templateName, $this->_templateParams );
		$logInfo = TemplateEngineAdapter::getLog($this->_templateName);
		$renderTime = $logInfo['renderTime'];
		// Show debug info for admins
		if ($this->_conf ['debug_on'] == 1 and $this->_SDK->is_admin()) {
			echo '<div class="debug-info"><h6>Debug info</h6><p>ClassName: '.get_class($this).'<br>Template engine: '.$this->_templateEngine
				.'<br>Cache mode: '.$this->_caching
				.'<br>Cache lifetime: '.$this->_cacheLifetime
				.'<br>Debug mode: '.$this->_debug.'<br>';
			if (isset( $getDataTime ))
				echo 'GetData time: ' . $getDataTime . ' sec<br>';
			if (isset( $renderTime ))
				echo 'Render time: ' . $renderTime . ' sec<br>';
			echo 'To disable this message set "conf[debug_on]" property into "false"</p></div>';
		}
		$this->postIndexHook();
	}	
	
	protected function getData() {}
	protected function postIndexHook() {}
}