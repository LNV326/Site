<?php

namespace Controller;

use Controller\AbstractSiteController;

class UploadsController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'UploadsTemplate.php';
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		$showForm = false;
		if ($this->_nfs->input['load']=='go') {
			include 'sources/upload.php';
			get_links();
		} else {
			$called=1;
			$showForm = true;
			include 'sources/upload.php';
		}
		return array(
				'showForm' => $showForm,
				'exts' => $ext
		);

	}

}