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
class ChatControllerDB extends AbstractSiteController {
	
	protected $_templateName = 'ChatTemplate.php';
		
	protected function getData() {
		// Проверка показывать ли юзеру команды для модеров
		$this->_DB->query( "SELECT chat_mod FROM ibf_members WHERE id='" . $this->_sdk_info [id] . "' LIMIT 1;" );
		$row = $this->_DB->fetch_row();
		return array(
				'showModCommands' => $row['chat_mod'] == 1 ? true : false
		);
	}
}