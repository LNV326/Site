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
class InfoControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'InfoTemplate.php';
	
	protected function getData() {
		return array(
				'site_name' => $this->_conf['site_name'],
				'site_url' => $this->_conf['site_url'],
				'banner_desc' => $this->_conf['banner_desc']
		);

	}

}