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
 */
class LoginControllerDB extends AbstractSiteController {
	
	protected $_caching = 0; // No caching
	protected $_templateName = "modules/auth.tpl";
	
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