<?php if ($out['newTopicCreated'] == true) { ?>
<p style='margin-top: 0.5cm; margin-bottom: 0.1cm; margin-left: 0.7cm'>
	<b>Результаты:</b> <br>Ваша новость отправлена! После проверки администрацией она будет добавлена в новости сайта.
</p>
<?php } ?>

<?php if ($out['notAuthorized'] == true) {?>
<p style='margin-top: 0.5cm; margin-bottom: 0.1cm; margin-left: 0.7cm'>
	<b>Ошибка:</b> <br>Вы не авторизованы на сайте.
</p>
<?php } ?> 

<? if ($out['showPreview'] == true) { ?>
<p class=normal>Предварительный просмотр введённой информации:</p>
<?php if ($out['errorEmptyTitle'] == true) {?>
<p class=normal>
	<b>Внимание</b>: Заголовок новости не указан!
</p>
<?php }?>
<table align=center cellspacing=0 cellpadding=0 style='border: 1 dashed gray; width: 98%; margin-top: 3pt'>
	<tr>
		<td>

			<table class=tl align=center cellspacing=0 cellpadding=0 style='width: 98%; margin-top: 5pt'>
				<tr>
					<td><img src='style/<?php echo $style_id;?>/img/tll.gif'></td>
					<td width=100%><b><?php echo $out['topicTitle'];?></b></td>
					<td><img src='style/<?php echo $style_id;?>/img/tlr.gif'></td>
				</tr>
			</table>

			<table align=center cellspacing=0 cellpadding=0 style='width: 98%; margin-top: 5pt'>
				<tr>
					<td><div class=news><?php echo $out['postInHTML'];?></div></td>
				</tr>
			</table>

			<table class=tl align=center cellspacing=0 cellpadding=0 style='width: 98%; margin-top: 5pt'>
				<tr>
					<td><img src='style/<?php echo $style_id;?>/img/tll.gif'></td>
					<td width=100%><?php echo $this->_lang[posted]; ?>: <a href='forum/index.php?showuser=<?php echo $out['authorId'];?>' target='_blank'><?php echo $out['authorName'];?></a>
						| <?php echo $this->_lang[source];?>: <a href='http://<?php echo $out['description'];?>/' target='_blank'><?php echo $out['description'];?></a></td>
					<td><img src='style/<?php echo $style_id;?>/img/tlr.gif'></td>
				</tr>
			</table>

		</td>
	</tr>
</table>
<?php } ?>

<?php if ($out['showForm'] == true) {
	if ($out['errorEmptyTitle'] == true) { ?>
<p class=normal>
	<b>Ошибка:</b> Ваша новость не отправлена! Отсутствует заголовок!
</p>
<?php } ?>
<script type='text/javascript' src='./forum/html/codes.js'></script>
<script type='text/javascript' src='./forum/html/topic.js'></script>
<script language='javascript1.2' type='text/javascript'>
		<!--
		var MessageMax = "";
		var Override = "";
		MessageMax = parseInt(MessageMax);
		if ( MessageMax < 0 ){
			MessageMax = 0;
		}
		function bbc_pop(){
			window.open('./forum/index.php?act=legends&CODE=bbcode&s=','Legends','width=700,height=500,resizable=yes,scrollbars=yes');
		}
		function CheckLength() {
			MessageLength = document.REPLIER.Post.value.length;
			message = "";
			if (MessageMax > 0) {
				message = "Сообщение: Максимально допустимая длина " + MessageMax + " символов.";
			} else {
				message = "";
			}
			alert(message + " Вами использовано " + MessageLength + " символов.");
		}
		
		function ValidateForm(isMsg) {
		MessageLength = document.REPLIER.Post.value.length;
		errors = '';
		if (isMsg == 1)
		{
		if (document.REPLIER.msg_title.value.length < 2)
		{
		errors = 'Необходимо ввести заголовок темы!';
		}
		}
		if (MessageLength < 2) {
		errors = 'Вы должны ввести текст сообщения!';
		}
		if (errors != '' && Override == '') {
		alert(errors);
		return false;
		} else {
		document.REPLIER.submit.disabled = true;
		return true;
		}
		}
		// IBC Code stuff
		var text_enter_url = 'Введите полный URL ссылки';
		var text_enter_url_name = 'Введите название сайта';
		var text_enter_image = 'Введите полный URL изображения';
		var text_text = '';
		var error_no_url = 'Вы должны ввести URL';
		var error_no_title = 'Вы должны ввести название';
		var prompt_start = 'Введите текст для форматирования';
		var help_bold = 'Жирный текст (alt + b)';
		var help_italic = 'Наклонный текст (alt + i)';
		var help_under = 'Подчёркнутый текст (alt + u)';
		var help_close = 'Закрытие всех открытых тэгов';
		var help_url = 'Добавить ссылку (alt+ h)';
		var help_img = 'Добавить изображение (alt + g)';
		var help_text = 'Выравнивание текста';
		var help_quote = "Ввод Цитаты (alt + q)";
		var help_font = "Выбор типа шрифта";
		var help_size = "Выбор размера шрифта";
		var help_color = "Выбор цвета шрифта";
		var help_click_close = 'Нажмите на кнопку для закрытия';
		var help_transit = 'Перекодировать транслит на русский (alt + t)'; 
		//-->
		</script>
<p class=normal>
	C помощью данной возможности вы можете добавить новости на сайт! Правила:<br> - запрещена любая рекламма.<br> - текст новостей, должен быть написан грамотным языком.<br> Нарушение данных правил будет наказываться в соответствии с <a href='/forum/index.php?act=rules' target='_blank'>правилами
		общения</a> на форуме.
</p>

<table align=center class=tl cellspacing=0 cellpadding=0>
	<tr>
		<td><img src='style/<? echo $style_id; ?>/img/tll.gif'></td>
		<td width=98%>&nbsp;<b>Добавляем новость</b></td>
		<td><img src='style/<? echo $style_id; ?>/img/tlr.gif'></td>
	</tr>
</table>

<form name='REPLIER' action='index.php?page=add_news&send=1' method='post' onsubmit='return ValidateForm()' style='margin: 3pt'>
	<table align='center' cellspacing='1' cellpadding='1' style='border: 1px solid #555555; width: 98%; margin-top: 5pt'>
		<tr bgcolor='#313131'>
			<td><p>Тема новостей (Заголовок):</p></td>
			<td width=25% align=right valign='top'><input type='text' size='40' maxlength='50' name='TopicTitle' value='<?echo $this->_nfs->convert_html($this->_nfs->input[TopicTitle]);?>' tabindex='1' class='forminput'></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#313131'>
			<td><p>
					Источник информации (если есть).<br> Пример www.nd4spd.ws. (<b>http://</b> не нужно):
				</p></td>
			<td align=right valign='top'><input type='text' size='40' maxlength='40' name='TopicDesc' value='<?echo $this->_nfs->convert_html($out['description']);?>' tabindex='2' class='forminput'></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#424242'>
			<td colspan=2><p align=center>
					Выбор режима редактора: [ <input type='radio' name='bbmode' value='ezmode' onclick='setmode(this.value)'>&nbsp;<b>Новичёк</b> ] -=- [ <input type='radio' name='bbmode' value='normal' onclick='setmode(this.value)' checked='checked'>&nbsp;<b>Профи</b> ]
				</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#323232'>
			<td colspan=2 align=center><input type='button' accesskey='b' value=' B ' onclick='simpletag("B")' name='B' class=forminput style="font-weight: bold" onmouseover="hstat('bold')" /> <input type='button' accesskey='i' value=' I ' onclick='simpletag("I")' name='I' class=forminput
				style="font-style: italic" onmouseover="hstat('italic')" /> <input type='button' accesskey='u' value=' U ' onclick='simpletag("U")' name='U' class=forminput style="text-decoration: underline" onmouseover="hstat('under')" /> <select name='ffont'
				onchange="alterfont(this.options[this.selectedIndex].value, 'font')" onmouseover="hstat('font')" class=forminput>
					<option value='0'>Шрифт текста</option>
					<option value='Arial' style='font-family: Arial'>Arial</option>
					<option value='Times' style='font-family: Times'>Times</option>
					<option value='Courier' style='font-family: Courier'>Courier</option>
					<option value='Impact' style='font-family: Impact'>Impact</option>
					<option value='Geneva' style='font-family: Geneva'>Geneva</option>
					<option value='Optima' style='font-family: Optima'>Optima</option>
			</select> <select name='fsize' onchange="alterfont(this.options[this.selectedIndex].value, 'size')" onmouseover="hstat('size')" class=forminput>
					<option value='0'>Размер текста</option>
					<option value='7'>Малый</option>
					<option value='12'>Большой</option>
					<option value='16'>Огромный</option>
			</select> <select name='fcolor' onchange="alterfont(this.options[this.selectedIndex].value, 'color')" onmouseover="hstat('color')" class=forminput>
					<option value='0'>Цвет текста</option>
					<option value='white' style='color: white'>Белый</option>
					<option value='blue' style='color: blue'>Синий</option>
					<option value='red' style='color: red'>Красный</option>
					<option value='purple' style='color: purple'>Фиолетовый</option>
					<option value='orange' style='color: orange'>Оранжевый</option>
					<option value='yellow' style='color: yellow'>Жёлтый</option>
					<option value='gray' style='color: gray'>Серый</option>
					<option value='green' style='color: green'>Зелёный</option>
			</select> <select name='ftext' onchange="alterfont(this.options[this.selectedIndex].value, 'align')" onmouseover="hstat('text')" class=forminput>
					<option value='0'>Выравнивание</option>
					<option value='left'>По левому краю</option>
					<option value='center'>По центру</option>
					<option value='right'>По правому краю</option>
			</select> <br>
				<p style='margin-top: 3pt; margin-bottom: 3pt'>
					<input type='button' accesskey='h' value=' WWW ' onclick='tag_url()' name='url' class=forminput onmouseover="hstat('url')"> <input type='button' accesskey='g' value=' IMG ' onclick='tag_image()' name='img' class=forminput onmouseover="hstat('img')"> <input type='button' accesskey='q'
						value=' QUOTE ' onclick='simpletag("QUOTE")' name='QUOTE' class=forminput onmouseover="hstat('quote')"> <input type='button' accesskey='t' value=' TRANSLIT ' onClick='rusLang()' name="TRANSLIT" class=forminput onMouseOver="hstat('transit')"> <input type='button' onclick='javascript:closeall()'
						onmouseover="hstat('close')" class=forminput value="Close all tags">
				</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#424242'>
			<td colspan=2><p>
					На данный момент открыто тэгов: <input type='text' name='tagcount' size='2' maxlength='3' style='text-align: center; border: 0px; font-weight: bold;' readonly='readonly' class='input' value='0'> <input type='text' name='helpbox' size='70' maxlength='120' style='width: auto; border: 0px'
						readonly='readonly' class=input value='Подсказки к функциям... -=- Need For Speed World Site'>
				</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#323232'>
			<td colspan=2><p align=center>
					<textarea cols='100' rows='10' name='Post' onkeydown="function(e)" tabindex='3' class='textinput' style='width: 100%; height: 75px'><?echo $out['post']; ?></textarea>
					<br>
				
				<div align='left' style='float: left'>
					<input type="button" value=" - " onclick='std_window_resize( -75 );' id="rtesizeminus" class=forminput> <input type="button" value=" + " onclick='std_window_resize( 75 );' id="rtesizeplus" class=forminput>
				</div>
				<div align='right'>
					[<a onclick='javascript:reset_textarea()' style="cursor: hand">Очистить</a>] [<a onclick='javascript:CheckLength()' style="cursor: hand">Проверить длину сообщения</a>] [<a onclick='javascript:bbc_pop()' style="cursor: hand">Помощь по кодам</a>]
				</div>
				</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#313131'>
			<td colspan=2><p align=center>
					<input type='submit' name='submit' value='Создать тему' tabindex='4' class='forminput' accesskey='s'> <input type='submit' name=preview value='Предварительный просмотр' tabindex='5' class='forminput'>
				</p></td>
		</tr>
	</table>
</form>
<?php } ?>