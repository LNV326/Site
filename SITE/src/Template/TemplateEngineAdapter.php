<?php

namespace Template;

use Template\TemplateEngineInterface;
use Template\PHPEngineAdapter;
use Template\SmartyEngineAdapter;
use Utils\Debug;
use Utils\SingletonInterface;

/**
 * The base class for any template adapter class.
 * It contains base debuggin and loggin functions and route each render query from Controllers to needed adapter using template file name.
 * For example: "qwery.tpl" will use SmartyEngineAdapter and "qwerty.php" will use PHPEngineAdapter, and there is no difference for Controller witch adapter will be used.
 * @author NLukyanov
 *
 */
abstract class TemplateEngineAdapter implements TemplateEngineInterface, SingletonInterface {
	
	/* ====================== */
	/* ======= STATIC ======= */
		
	protected static $_logging = array();
	protected static $_debug = null;
	
	protected static $_temp_milestone_time = 0;
	protected static $_temp_milestone_mu = 0;
	
	/**
	 * Return the log info about rendered template with given name
	 * @param string $templateName
	 * @return array
	 */
	public static function getLog( $templateName ) {
		if ( !is_null($templateName) )
			return self::$_logging[$templateName];
		else return self::$_logging;
	}
	
	/**
	 * Save new row to the log info about rendered template with given name
	 * @param string $templateName
	 */
	public function setLog( $templateName, $rt, $mu, $isFromCache ) {
		if ( is_null(self::$_logging[$templateName]) )
		self::$_logging[$templateName] = array(
				'templateName' => $templateName,
				'engine' => get_called_class(),
				'cachingMode' => $this->getCachingMode(),
				'cacheLifetime' => $this->getCacheLifetime(),
				'isFromCache' => $isFromCache,
// 				'memoryUsage' => array(),
// 				'timer' => array(),
				'checkIsCacheTime' => 0,
				'checkIsCacheMU' => 0,
				'renderTime' => 0,
				'renderMU' => 0,
		);
	}
	
	public function setLogIsCachedMilestone($templateName) {
		self::$_temp_milestone_time = self::$_debug->endTimer();
		self::$_temp_milestone_mu = self::$_debug->endMemUsage();
	}
	
	/**
	 * Return dynamically deternited template adapter (implements of TemplateEngineInterface) using template file name.
	 * @param string $templateName
	 * @return TemplateEngineAdapter
	 * @throws \UnexpectedValueException
	 */
	public static function getInstanceBase($templateName) {
		// Initialize debugger
		if ( is_null(self::$_debug) ) {
			self::$_debug = new Debug();
		}
		// Choose the template engine
		$filetype = strtolower(substr(strrchr($templateName, '.'), 1));
		switch ($filetype) {
			case 'php' : return PHPEngineAdapter::getInstance();
			case 'tpl' :  return SmartyEngineAdapter::getInstance();
			case 'twig' : throw new \UnexpectedValueException('Twig extension is not supported at this time');
			default : throw new \UnexpectedValueException("Can't choose the template engine for template \"$templateName\" - unknown file type");
		}
	}
	
	/* ======================= */
	/* ======= DYNAMIC ======= */
	
	/**
	 * Protected constructor for nobody it this but this class itself (Singleton)
	 */
	protected function __construct() {}
	
	/**
	 * Protected cloner for nobody call it but this class itself (Singleton)
	 */
	protected function __clone() {}
	
	public final function display( $templateName, $templateParams, $cacheId = null ) {
		print $this->render($templateName, $templateParams, $cacheId);
	}
			
	public final function render( $templateName, $templateParams, $cacheId = null ){
		self::$_debug->startTimer();
		self::$_debug->startMemUsage();
		$result = $this->renderExecute($templateName, $templateParams, $cacheId);
		$this->setLog($templateName, self::$_debug->endTimer(), self::$_debug->endMemUsage(), 0);
		self::$_logging[$templateName]['renderTime'] = self::$_debug->endTimer();
		self::$_logging[$templateName]['renderMU'] = self::$_debug->endMemUsage();
		self::$_logging[$templateName]['isFromCache'] = 0;
		return $result;
	}
	
	public final function getCachedResult($templateName, $cacheId = null) {
		self::$_debug->startTimer();
		self::$_debug->startMemUsage();
		$result = $this->getCachedResultExecute($templateName, $cacheId);
		$this->setLog($templateName, self::$_debug->endTimer(), self::$_debug->endMemUsage(), 1);
		self::$_logging[$templateName]['checkIsCacheTime'] = self::$_debug->endTimer();
		self::$_logging[$templateName]['checkIsCacheMU'] = self::$_debug->endMemUsage();	
		return $result;
	}
	
	/**
	 * Return the result of rendering the template with given name (contains the template adapter logic)
	 * @param string $templateName
	 * @param array $templateParams
	 * @param string $cacheId
	 */
	protected abstract function renderExecute( $templateName, $templateParams = array(), $cacheId = null );
		
	public function clearAllCache () {
		$this->_smarty->caching = $this->_caching;
		$this->_smarty->clear_cache($this->_templateName);
	}
	
}