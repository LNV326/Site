<?php

namespace Controller;

use Controller\AbstractSiteController;

class SMSMoneyController extends AbstractSiteController {
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		echo <<< EOF
<p class=normal>C помощью данной услуги вы можете материально помочь сайту имея на руках сотовый телефон с поддержкой текстовых сообщений SMS!</p>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Средства в копилке:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>

<table align=center width=250px cellspacing=0 cellpadding=0><tr>
<td colspan=3><img src="files/rama_up.gif"></td></tr><tr>
<td><img src="files/rama_left.gif"></td>
<td width=98% style='background: white'><center><a href="http://smskopilka.ru/?info&id=2679" title="Записки и баланс sms.копилки от Need For Speed World Site в новом окне" target="_blank"><img src="http://smskopilka.ru/iclient/2679/smskopilka.gif" border="0"></a></center></td>
<td><img src="files/rama_right.gif"></td>
</tr><tr><td colspan=3><img src="files/rama_down.gif"></td></tr>
</table>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Инструкция:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>

<p class=normal>Для получения подробных инструкций по отправке сообщения нажмите на копилку.</p>
EOF;
	}

}