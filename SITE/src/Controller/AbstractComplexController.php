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
		foreach ($this->_CONFIG as $item) {
			try {
				$this->$item();
			} catch (Exception $e) {
				// TODO dont do that!
			}
		}
	}
}