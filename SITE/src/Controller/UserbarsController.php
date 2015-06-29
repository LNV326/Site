<?php

namespace Controller;

use Controller\AbstractSiteController;

class UserbarsController extends AbstractSiteController {
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		echo <<< EOF
		<script language='JavaScript' type="text/javascript">
<!--
function Insert(text,my_bar){
	if (text) {
	    var site_url="<? echo $this->_conf[site_url]; ?>";
	    if (my_bar=="1") {
		var bar_url="/forum/uploads/"+text;
	    } else {
		var bar_url="/files/userbars/"+text;
	    }
	    if (document.getElementById("code_forum1")) {
		var input=document.getElementById("code_forum1");
		input.value="[url=http://"+site_url+"][img]http://"+site_url+bar_url+"[/img][/url]";
	    }
	    if (document.getElementById("code_forum2")) {
		var input=document.getElementById("code_forum2");
		input.value="[url=http://"+site_url+"][img=http://"+site_url+bar_url+"][/url]";
	    }
	    if (document.getElementById("code_html")) {
		var input=document.getElementById("code_html");
		input.value="<a href='http://"+site_url+"' target='_blank'><img src='http://"+site_url+bar_url+"' border='0'></a>";
	    }
	} else {
		alert("Необратимая ошибка!");
	}
}
//-->
</script>

<p class=normal>В данном разделе находятся коды наших Userbar'ов которые вы можете разместить например на других форумах.</p>

<table align=center class=tl cellspacing=0 cellpadding=0><tr>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Код Userbar'a:</b></td>
<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
</tr></table>

<p class=normal>Для того чтобы воспользоваться нашими юзербарами на других форумах необходимо в скопировать представленный ниже код в свою подпись на других сайтах. В различных форумах синтаксис кодов может отличаться поэтому здесь представлены 2 варианта:<br>
1) Код для Форумов №1:<br>
<textarea cols='80' rows='2' id='code_forum1' class='textinput'>Для получения кода кликните на нужный вам Userbar!</textarea><br>
2) Код для Форумов №2:<br>
<textarea cols='80' rows='2' id='code_forum2' class='textinput'>Для получения кода кликните на нужный вам Userbar!</textarea><br>
3) HTML Код:<br>
<textarea cols='80' rows='2' id='code_html' class='textinput'>Для получения кода кликните на нужный вам Userbar!</textarea></p>
EOF;
//Юзербар пользователя на форуме
		if ((!preg_match( "/^http:\/\//", $this->_sdk_info[userbar])) and (preg_match("/^upload:bar-(?:\d+)\.(?:\S+)/", $this->_sdk_info[userbar] ))) {
 			$userbar_img = preg_replace( "/^upload:/", "", $this->_sdk_info[userbar] );
echo <<< EOF
<table align=center class=tl cellspacing=0 cellpadding=0>
	<tr>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
		<td width=98%>&nbsp;<b>Ваш текущий Userbar на нашем форуме:</b></td>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
	</tr>
</table>
<p class=normal>
EOF;
		echo "<a href='javascript:Insert(\"".$userbar_img."\",1)'>".$this->_std->get_userbar( $this->_sdk_info[userbar], 1, "350x20" )."</a><br>";
		echo "</p><p class=normal>Примечание: Если вы измените свой Userbar в профиле то он измениться и там, где будет установлен этот код. Если вы удалите свой Userbar из профиля либо загрузите новый, но в другом формате, то там где установлен этот код будет показываться ошибка!</p>";

echo <<< EOF
<table align=center class=tl cellspacing=0 cellpadding=0>
	<tr>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
		<td width=98%>&nbsp;<b>Наши Userbar'ы для размещения на других сайтах:</b></td>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
	</tr>
</table>
EOF;
			$dir = $this->_conf [site_path] . "files/userbars/";
// 			$url = "http://" . $this->_conf [site_url] . "/files/userbars/";
			$no_view = array (
					"..",
					".",
					"Thumbs.db",
					"thumbs.db" 
			);
			if ($dh = opendir( $dir )) {
				while ( ! (($file = readdir( $dh )) === false) ) {
					if (is_file( "$dir/$file" ) and (! in_array( $file, $no_view ))) {
						echo "<p align=center style='margin:5pt'><a href='javascript:Insert(\"" . $file . "\",0)'><img src='/files/userbars/" . $file . "' border='0'></a></p>";
					}
				}
			} else {
				echo "<p class=normal>Ошибка при чтении каталога. Обратитесь к администрации!</p>";
			}
			closedir( $dh );

		}
	}
}