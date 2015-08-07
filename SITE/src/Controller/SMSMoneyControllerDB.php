<?php

namespace Controller;

use Controller\AbstractSiteController;

/**
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0 Tested 08/08/2015
 *
 * Refactoring from old site engine version (year 2003). All HTML code transfered to template file.
 *
 * @todo Need to remove this module in new version of site engine
 */
class SMSMoneyControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'SMSMoneyTemplate.php';
	
	protected function getData() {
		return null;
	}

}