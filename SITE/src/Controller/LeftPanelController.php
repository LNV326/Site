<?php

namespace Controller;

use Controller\AbstractSiteController;
use Service\NavigationService;

class LeftPanelController extends AbstractSiteController {
	
	protected $_templateName = './../mainmenu_left.php';
	
	protected function getData() {
		$ns = NavigationService::getInstance();
		return array(
				'mainMenu' => $ns->getMainMenu(),
				'menu' => $ns->getMenuCategories()
		);
	}
	
}