<?php

namespace Controller;

use Controller\AbstractSiteController;

class LinksController extends AbstractSiteController {
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		echo <<< EOF
<p class=normal>В данном разделе находятся ссылки на сайты похожей тематики! Если вы хотите обменяться с нашим сайтом баннером, то вам необходимо связаться с администратором (<a href='index.php?page=contact'>связь</a>). В письме необходимо указать: ссылку на баннер (468х60), описание сайта и его название.<br>
<b>Условия обмена:</b><br>
1) Посещаемость более 250 человек в день<br>
2) Постоянно обновляемый сайт<br>
3) Размещение нашего баннера 468х60 в разделе ссылок на вашем сайте<br>
4) Тематика вашего сайта - автомобильные симуляторы</p>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Need For Speed:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>
<center><a title="Сайт об автосимуляторах Need For Speed и не только" href="http://nfs2003.msk.ru" target="_blank"><img src="http://nfs2003.msk.ru/images/banner1.gif" border="0" width="88" height="31"></a></center>
<br>
<center><a href="http://nfs-racing.com/" target="_blank" title="Вcё о серии NFS."><img border="0" src="http://www.nfs-racing.com/banner/bann2.gif" width="468" height="60"></a></center>
<br>
<center><a href="http://nfs-community.com/" target="_blank"><img src="http://nfs-community.com/DesignNew/Images/468x60_2.gif" alt="Need For Speed Community Portal"></a></center>

<?/*
<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Другие игры:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>
<center><a href="http://lineage2info.ru" target="_blank">Lineage2 - База знаний</a></center>
<br>
<center><a href="http://cm-racing.ru" target="_blank"><img src="http://cm-racing.ru/img/obmen468x60.jpg" alt="Всё об играх Race Driver GRiD, Серии Colin McRae, F1 2010 (www.cm-racing.ru)" border=0></a></center>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Сотовые телефоны:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>
<center><a href="http://www.cx75planet.ru/" target="_blank"><img border="0" src="http://www.cx75planet.ru/misc/banners/banner_468x60.jpg" width="468" height="60" alt="Все для любителей SIEMENS - the planet of siemens x75"></a></center>
*/
?>
EOF;

	}

}