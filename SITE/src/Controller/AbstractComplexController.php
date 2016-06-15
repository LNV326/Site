<?php

namespace Controller;

use Controller\AbstractSiteController;

class AbstractComplexController extends AbstractSiteController {
		
	/**
	 * List of functions in this class that should be called
	 * @var unknown
	 */
	protected $_CONFIG = array();
	
	protected function getData() {
		$result = array();
		foreach ($this->_CONFIG as $item) {
			try {
				$result[$item] = $this->$item();
			} catch (Exception $e) {
				// TODO dont do that!
			}
		}
		return $result;
	}
}