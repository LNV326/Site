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
class ContactControllerDB extends AbstractSiteController {
	
	protected $_templateName = 'ContactTemplate.php';	
	
	protected function getData() {
		
		//Подгрузка необходимостей )
		include "admin/ad_editor/functions/global.php"; // TODO For what purpose include this?
		//Куда будем отправлять
		if ($this->_nfs->input['type']=='sms') {
			$mailto="79049837875@sms.ycc.ru";
		} else {
			$mailto=$this->_conf[site_name]." <".$this->_conf[webmaster_email].">";
		}
		if ($this->_nfs->input['send']=='go') {
			//Тема (имя юзера)
			$subject = $this->_nfs->input[subject];
			if (strlen($subject) <= 3) {
				get_rezult('Имя пользователя должно быть не менее 4 символов!');
				get_links();
				$error=TRUE;
			}
			//Откого отправлять
			$mailfrom=$this->_nfs->input['mailfrom'];
			if ((!preg_match("|^[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,4}$|i", $mailfrom)) and (!$error)){
				get_rezult('Введен некорректный E-Mail адрес!');
				get_links();
				$error=TRUE;
			}
			//Сообщение
			$message = $this->_nfs->input[message];
			if ((strlen($message) <= 9) and (!$error)) {
				get_rezult('Текст сообщения слишком короткий! Минимум 10 символов!');
				get_links();
				$error=TRUE;
			}
			if (!$error) {
				/* получатели */
// 				$to  = $this->_conf[site_name]." <".$this->_conf[webmaster_email].">";
				/* Для отправки HTML-почты вы можете установить шапку Content-type. */
				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				/* дополнительные шапки */
				$headers .= "Date: ".date("m.d.Y (H:i:s)",time())."\r\n";
				$headers .= "From: ".$mailfrom." <".$mailfrom.">\r\n";
				/* и теперь отправим из */
				//error_reporting(-1);
				//ini_set('error_reporting', E_ALL);
				// TODO Can't test this. Need to retest mail sendind.
				if (@mail($mailto, $subject, $message, $headers)) {
					get_rezult('Сообщение отправлено!');
					get_links();
					$error=TRUE;
				} else {
					get_rezult('Сообщение не отправлено! Для решения проблемы обратитесь к администратору используя другой способ связи!');
					get_links();
					$error=TRUE;
				}
			}
		}
		//Не выводить форму если произошла ошибка
		if (!$error) {
			//Вывод формы для отправки
			$types = array(	0 => array( 'mail', 'на е-mail адрес'),
					1 => array( 'sms', 'sms на телефон'),
			);
			$type_list=make_select('type',$types,$this->_nfs->input['type']);
		}
		return array(
				'showForm' => $error,
				'type' => isset($this->_nfs->input['type']) ? $this->_nfs->input['type'] : 'mail',
				'typeList' => $type_list
		);
	}

}