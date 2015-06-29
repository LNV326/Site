<?php

namespace Controller;

use Controller\AbstractSiteController;

class InfoController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'InfoTemplate.php';
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		return array(
				'site_name' => $this->_conf['site_name'],
				'site_url' => $this->_conf['site_url'],
				'banner_desc' => $this->_conf['banner_desc']
		);

	}

}