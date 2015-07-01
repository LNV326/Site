<?php var_dump($out);?>
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
<td><img src="<? echo "style/".$style_id."/img" ;?>/tll.gif"></td>
<td width=98%>&nbsp;<b>Код Userbar'a:</b></td>
<td><img src="<? echo "style/".$style_id."/img" ;?>/tlr.gif"></td>
</tr></table>

<p class=normal>Для того чтобы воспользоваться нашими юзербарами на других форумах необходимо в скопировать представленный ниже код в свою подпись на других сайтах. В различных форумах синтаксис кодов может отличаться поэтому здесь представлены 2 варианта:<br>
1) Код для Форумов №1:<br>
<textarea cols='80' rows='2' id='code_forum1' class='textinput'>Для получения кода кликните на нужный вам Userbar!</textarea><br>
2) Код для Форумов №2:<br>
<textarea cols='80' rows='2' id='code_forum2' class='textinput'>Для получения кода кликните на нужный вам Userbar!</textarea><br>
3) HTML Код:<br>
<textarea cols='80' rows='2' id='code_html' class='textinput'>Для получения кода кликните на нужный вам Userbar!</textarea></p>
<?php if ($out['showForm'] == true) {?>
<table align=center class=tl cellspacing=0 cellpadding=0>
	<tr>
		<td><img src="<? echo "style/".$style_id."/img" ;?>/tll.gif"></td>
		<td width=98%>&nbsp;<b>Ваш текущий Userbar на нашем форуме:</b></td>
		<td><img src="<? echo "style/".$style_id."/img" ;?>/tlr.gif"></td>
	</tr>
</table>
<p class=normal>
<a href='javascript:Insert(\"<?php echo $out['userbar_img']?>\",1)'><?php echo $this->_std->get_userbar( $this->_sdk_info[userbar], 1, "350x20" )?></a><br>
</p><p class=normal>Примечание: Если вы измените свой Userbar в профиле то он измениться и там, где будет установлен этот код. Если вы удалите свой Userbar из профиля либо загрузите новый, но в другом формате, то там где установлен этот код будет показываться ошибка!</p>
<table align=center class=tl cellspacing=0 cellpadding=0>
	<tr>
		<td><img src="<? echo "style/".$style_id."/img" ;?>/tll.gif"></td>
		<td width=98%>&nbsp;<b>Наши Userbar'ы для размещения на других сайтах:</b></td>
		<td><img src="<? echo "style/".$style_id."/img" ;?>/tlr.gif"></td>
	</tr>
</table>
<?php foreach($out['files'] as $file) {?>
	<p align=center style='margin:5pt'><a href='javascript:Insert(\"<?php echo $file;?>\",0)'><img src='/files/userbars/<?php echo $file;?>' border='0'></a></p>
<?php }?>
<?php if ($out['error'] == true) {?>
	<p class=normal>Ошибка при чтении каталога. Обратитесь к администрации!</p>
<?php }?>
<?php }?>