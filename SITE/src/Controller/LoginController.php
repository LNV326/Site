<?php

namespace Controller;

use Controller\AbstractSiteController;

class LoginController extends AbstractSiteController {
	
	protected $_caching = 0; // No caching
	protected $_templateName = "modules/auth.tpl";
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		if (!empty($_GET['error'])) {
			switch ($_GET['error']) {
				case 'name': {
					$error="Необходимо ввести имя пользователя от 3 до 15 символов.";
					break;
				}
				case 'pass': {
					$error="Необходимо ввести пароль от 3 до 15 символов.";
					break;
				}
				case 'check': {
					$error="Вы указали не правильный логин или пароль.";
					break;
				}
			}
			$this->_smarty->assign('error', $error);
		}
	}

}