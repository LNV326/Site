<?php

namespace Controller;

use Controller\AbstractSiteController;

/**
 * 
 * @author Nikolay Lukyanov
 * 
 * @version 1.0
 * Refactoring from vertion of 2003 year, all HTML transfered to template file.
 * 
 * TODO Maybe it's good to do the article and remove this controller
 *
 */
class AdverControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'AdverTemplate.php';	
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		return null;
	}

}