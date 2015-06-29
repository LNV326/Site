<?php

namespace Controller;

use Controller\AbstractSiteController;

class AboutControllerDB extends AbstractSiteController {
	
	protected function  getData() {
		$site_name = $this->_conf['site_name'];
		echo <<< EOF
<p class=normal>ФИО: Дёмин Алексей Сергеевич / 05.12.1987<br>
Увлекаюсь автомобилями марки Porsche, веб-программированием. Живу в Екатеринбурге, Россия.</p>
<p class=normal><b>Контакты</b>:<br>
WEB: <a href='http://www.allex.me/' target="_blank" alt="Персональная страница">www.allex.me</a><br>
E-Mail: <a href='http://www.allex.me/feedback/' target="_blank" alt="Обратная связь">написать</a><br>
ЛС: <a href='forum/index.php?act=Msg&amp;CODE=04&amp;MID=1' target="_blank" alt="Личное сообщение на форуме">отправить</a></p>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td ><img src="style/$this->_style_id/img/tll.gif"></td>
<td width=98%>&nbsp;<b>Огромный респект:</b></td>
<td><img src="style/$this->_style_id/img/tlr.gif"></td>
</tr></table>

<p class=normal>Так же не стоит забывать о тех, кто помогает сайту чем может. Это наши Cоадмины, Модераторы и НьюсМейкеры! Без них сайта уже бы давненько не было!<br>
<a href="forum/index.php?act=Stats&CODE=leaders" alt="Администрация $site_name" target="_blank">Администрация проекта $site_name"</a>.</p>
EOF;
	}
}