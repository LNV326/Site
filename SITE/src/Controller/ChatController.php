<?php

namespace Controller;

use Controller\AbstractSiteController;

class ChatController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'ChatTemplate.php';
		
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		// Проверка показывать ли юзеру команды для модеров
		$this->_DB->query( "SELECT chat_mod FROM ibf_members WHERE id='" . $this->_sdk_info [id] . "' LIMIT 1;" );
		$row = $this->_DB->fetch_row();
		return array(
				'showModCommands' => $row['chat_mod'] == 1 ? true : false
		);
	}
}