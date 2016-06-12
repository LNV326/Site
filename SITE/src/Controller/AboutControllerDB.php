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
 * TODO Maybe it's good to do the article and remove this controller
 *
 */
class AboutControllerDB extends AbstractSiteController {
	
	protected $_templateName = 'AboutTemplate.php';
	
	protected function getData() {
		return array('site_name' => $this->_conf['site_name']);
	}
}