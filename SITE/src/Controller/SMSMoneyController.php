<?php

namespace Controller;

use Controller\AbstractSiteController;

class SMSMoneyController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'SMSMoneyTemplate.php';
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		return null;
	}

}