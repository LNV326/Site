<?php

namespace Controller;

use Controller\AbstractSiteController;

class LinksController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'LinksTemplate.php';
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		return null;
	}

}