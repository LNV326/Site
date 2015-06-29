<?php

namespace Controller;

use Controller\AbstractSiteController;

class InfoController extends AbstractSiteController {
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		echo <<< EOF
<p class=normal>Сайт о легендарной серии игр Need For Speed! На сайте вы сможете найти огромное кол-во информации которая возможно будет вам необходима! Сможете узнать мнения других людей о NFS, пообщаться на форуме о игре, и не только! Приятного просмотра!</p>

<p class=normal>Дата появления сайта - 01.01.03</p>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td ><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Наши кнопки:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>

<p class=normal>Если вы разместите нашу кнопку на своём сайте, мы будем вам очень благодарны !</p>

<p class=normal><b>Код кнопки (88x31):</b></p>
<p class=normal>HTML:</br>
&lt;!-- Start <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --><br>
&lt;a href="http://<?echo $this->_conf[site_url];?>">&lt;img src="http://<?echo $this->_conf[site_url];?>/files/banner.gif" alt="<?echo $this->_conf[banner_desc];?> (www.<?echo $this->_conf[site_url];?>)" border=0>&lt;/a><br>
&lt;!-- End <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --></p>

<p class=normal><b>Пример кнопки:</b><br>
<!-- Start <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) -->
<a href="http://<?echo $this->_conf[site_url];?>"><img src="http://<?echo $this->_conf[site_url];?>/files/banner.gif" alt="<?echo $this->_conf[banner_desc];?> (www.<?echo $this->_conf[site_url];?>)" border=0></a><br>
<!-- End <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --></p>

<p class=normal><b>Код анимированной кнопки (468x60):</b><br>
<p class=normal>HTML:</br>
&lt;!-- Start <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --><br>
&lt;a href="http://<?echo $this->_conf[site_url];?>">&lt;img src="http://<?echo $this->_conf[site_url];?>/files/banner_468x60.gif" alt="<?echo $this->_conf[banner_desc];?> (www.<?echo $this->_conf[site_url];?>)" border=0>&lt;/a><br>
&lt;!-- End <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --></p>

<p class=normal><b>Пример анимированной кнопки:</b><br>
<!-- Start <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) -->
<a href="http://<?echo $this->_conf[site_url];?>"><img src="http://<?echo $this->_conf[site_url];?>/files/banner_468x60.gif" alt="<?echo $this->_conf[banner_desc];?> (www.<?echo $this->_conf[site_url];?>)" border=0></a><br>
<!-- End <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --></p>

<p class=normal><b>Код статической кнопки (468x60):</b><br>
<p class=normal>HTML:</br>
&lt;!-- Start <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --><br>
&lt;a href="http://<?echo $this->_conf[site_url];?>">&lt;img src="http://<?echo $this->_conf[site_url];?>/files/banner_468x60.jpg" alt="<?echo $this->_conf[banner_desc];?> (www.<?echo $this->_conf[site_url];?>)" border=0>&lt;/a><br>
&lt;!-- End <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --></p>

<p class=normal><b>Пример статической кнопки:</b><br>
<!-- Start <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) -->
<a href="http://<?echo $this->_conf[site_url];?>"><img src="http://<?echo $this->_conf[site_url];?>/files/banner_468x60.jpg" alt="<?echo $this->_conf[banner_desc];?> (www.<?echo $this->_conf[site_url];?>)" border=0></a><br>
<!-- End <?echo $this->_conf[site_name];?> (www.<?echo $this->_conf[site_url];?>) --></p>
EOF;

	}

}