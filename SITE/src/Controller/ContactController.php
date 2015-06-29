<?php

namespace Controller;

use Controller\AbstractSiteController;

class ContactController extends AbstractSiteController {
	
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		
		//Подгрузка необходимостей )
		include "admin/ad_editor/functions/global.php";
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
echo <<< EOF
		<script language="JavaScript">
		function maxlength_lang(form){
		   var maxlength=210;
		   str=form.message.value;
		   dlina=str.length;
		   if(dlina>maxlength)form.message.value=str.substring(0,maxlength);
		   form.lang.value=(maxlength-dlina);}
		   required = new Array("subject", "message");
		   required_show = new Array("свой ник", "сообщение");
		function SendForm () {
		   var i, j;
		   for(j=0; j<required.length; j++) {
		      for (i=0; i<document.forms[0].length; i++) {
		         if (document.forms[0].elements[i].name == required[j] && document.forms[0].elements[i].value == "" ) {
		            alert('Вы не написали ' + required_show[j] + '');
		            document.forms[0].elements[i].focus();
		             return false;
		          }
		      }
		   }
		   return true;
		}
		</script>
		<form name="sender" method="post" action="index.php?page=contact&type=<? $this->_nfs->input['type']; ?>&send=go" onsubmit="return SendForm();" style='margin:0pt'>
		<table align="center" border="0" cellspacing="1" cellpadding="0" width="98%"><tr>
		<td colspan=2 class=top height=18 align=center style='border: #323232 1px solid;background-color: #323232'><b>Написать автору сайта:</b> <? echo $type_list; ?></td>
		<td class=top height=18 align=center style='border: #323232 1px solid;background-color: #323232'><b>Заполните все поля</b></td>
		</tr><tr>
		<td class=top style="border: #323232 1px solid" height="18" align="center"  style="background-color: #323232" width="35%">
		Ваше имя: <input class=textinput type="text" name="subject" size="25">
		</td><td class=top style="border: #323232 1px solid" height="18" align="center"  style="background-color: #323232" width="30%">
		Ваш e-mail: <input class=textinput type="text" name="mailfrom" size="25">
		</td><td class=top style="border: #323232 1px solid" height="18" align="center"  style="background-color: #323232" width="35%">
		Вам доступно <input class=textinput type="text" name="lang" size="3" value="210"> символов
		</td></tr><tr>
		<td colspan=3 class=fon style="border: #313131 1px solid" height="90" align="center"  style="background-color: #323232" valign="center">
		<textarea class=textinput name="message" rows="4" cols="120" onChange="maxlength_lang(document.sender)" onKeyUp="maxlength_lang(document.sender)" onKeyDown="maxlength_lang(document.sender)" onClick="maxlength_lang(document.sender)" onKeyPress="maxlength_lang(document.sender)"></textarea><br>
		<input class=textinput type="submit" name="submit" style="width: 90pt; cursor: hand;" value="Отправить">&nbsp;<input class=textinput type="reset" name="reset" style="width: 90pt; cursor: hand;" value="Очистить">
		</td></tr>
		</table>
		</form>
EOF;
		}
	}

}