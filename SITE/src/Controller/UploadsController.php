<?php

namespace Controller;

use Controller\AbstractSiteController;

class UploadsController extends AbstractSiteController {
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		if ($this->_nfs->input['load']=='go') {
			include 'sources/upload.php';
			get_links();
		} else {
			echo <<< EOF
		<script language='javascript'>
		<!--
		function show_settings() {
			var selected = document.LoadForm.types.options[document.LoadForm.types.selectedIndex].value;
			if (selected == '1') {
				document.getElementById('new_items').style.display = "";
			} else {
				document.getElementById('new_items').style.display = "none";
			}
		}
		//-->
		</script>
		<p class=normal>Здесь вы можете добавить файлы в файловый архив сайта. Для этого вам необходимо ознакомиться с нашими правилами:</p>
		
		<table align=center class=tl cellspacing=0 cellpadding=0><tr>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
		<td width=96%>&nbsp;<b>Внимание! Правила загрузки для файлов</b></td>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
		</tr></table>
		<p class=normal>К файлам такого типа относятся программы. Файл такого типа попадает в файловый архив. Если вы хотите добавить такой файл, то вам необходимо сделать следующее:<br>
		1) Заархивировать программу с помощью WinRar'a!<br>
		2) Назвать файл соответсвующим образом, с указанием версии программы если она существует. Например (<b>trainer.exe</b>).<br>
		3) Убедиться, что размер файла не превышает <b><? echo $this->_conf[loadfile_size] ;?>Мб</b>. Если превышает, то свяжитесь с администратором, и если он посчитает нужным его добавить, то файл будет загружен.<br>
		4) Создать текстовый документ с тем-же именем, что и программа (<b>trainer.txt</b>), в котором написать полное описание программы.<br>
		5) Загрузить оба файла на сайт выбрав тип (<span style='color:orange'>Файл без рисунка</span>)!<br>
		6) Ждать пока файл появится (если всё сделано правильно)!</p>
		
		<table align=center class=tl cellspacing=0 cellpadding=0><tr>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
		<td width=96%>&nbsp;<b>Внимание! Правила загрузки для файлов с рисунками</b></td>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
		</tr></table>
		<p class=normal>К файлам такого типа относятся например новые винилы, автомобили или трассы. В файловом архиве такая подкатегория выделяется надпиcью (Другие файлы)! Если вы хотите добавить такой файл, то вам необходимо сделать следующее:<br>
		1) Поместить файлы в архив. Например если вы грузите новый автомобиль, то в архиве должны находиться файлы для установки!<br>
		2) Назвать созданный архив следующим образом: (<b>автор_категория_название.rar</b>) (строчными латинскими буквами). Например (<b>tarakan_pucar_xxx.rar</b>).<br>
		3) Сделать скриншот (в данном случае автомобиля) в игре! И назвать его так-же как и архив только с разширением файла "<b>*.jpg</b>" (<b>автор_категория_название.jpg</b>). В данном случае: (<b>tarakan_pucar_xxx.jpg</b>). Скриншот должен быть не модифицированным (не должно быть слоёв, например несколько ракурсов), разрешение рисунка - <b>160х120</b> пикселей!<br>
		4) Создать текстовый документ с тем-же именем, что и архив (В данном случае <b>tarakan_pucar_xxx.txt</b>), в котором написать полное описание файла (будет показано при скачивании)!<br>
		5) Загрузить три файла на сайт выбрав тип (<span style='color:orange'>Файл с рисунком</span>)!<br>
		6) Ждать пока файл появится (если всё сделано правильно)!</p>
		
		<table align=center class=tl cellspacing=0 cellpadding=0><tr>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tll.gif"></td>
		<td width=98%>&nbsp;<b>Примечание:</b></td>
		<td><img src="<? echo "style/".$this->_style_id."/img" ;?>/tlr.gif"></td>
		</tr></table>
		<p class=normal>Дополнительная информация:<br>
		1) Все загружаемые файлы должны быть названы строчными латинскими буквами. (primer.rar)<br>
		2) Для каждого файла создаётся отдельный архив.<br>
		3) В загружаемых файла не должно быть никакой рекламы, ссылок на сайты и т.д., если они не являются авторами предоставленного файла.<br>
		4) Если все правила будут соблюдены, то файл появится после проверки администрацией.</p>
		<br>
EOF;
			echo "<form enctype='multipart/form-data' method='post' action='index.php?page=uploads&load=go' style='margin:0pt' name='LoadForm'>";
			echo "<table align=center cellspacing=1 cellpadding=1 style='border:1px solid #555555;width:98%;margin-top:5pt'>\n";
			echo "<tr bgcolor='#424242'><td colspan=2><p align=center>Загрузка файлов:</p></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#323232'><td colspan=2><p align=normal>Допустимые для загрузки расширения файлов: <b>";
			$called=1;
			include 'sources/upload.php';
			foreach ( $ext as $exts ){
				echo $exts." ";
			}
			echo "<br></b>Максимальный размер загружаемого файла: <b>". round((( $max_file_size / 1024 ) / 1024) , 2)."MB";
			echo "</b></p></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#424242'><td colspan=2><p align=center>Тип файла:</p></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#313131'><td width=90%><p>Выберете тип загрузки файла! Какой тип выбрать читайте в правилах!</p></td>\n";
			echo "<td align=right width=10%><select name='types' onChange='show_settings()' class='forminput'>\n";
			echo "<option value='1'>Файл с рисунком</option>\n";
			echo "<option value='2'>Файл без рисунка</option>\n";
			echo "</select></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#424242'><td colspan=2><p align=center>Выберете файл для загрузки:</p></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#323232'><td colspan=2><p>Соблюдение правил в ваших-же интересах! Чтобы файл появился хотите вы а не мы!</p></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#323232'><td colspan=2>
		<p class=normal>Файл с описанием загружаемого файла:<br><input type='file' name='file[]' class=textinput size='35'></p>
		<p class=normal>Файл (архив, программа):<br><input type='file' name='file[]' class=textinput size='35'></p>
		<div id='new_items'><p class=normal>Скриншот (800х600):<br><input type='file' name='file[]' class=textinput size='35'></p></div>
		</td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#313131'><td colspan=2><p align=center><input name='Button' class=forminput type='button' value='Назад' onClick='javascript:history.go(-1)'> <input type='submit' class='forminput' name='LoadGo' value='Загрузить файлы'></p></td></tr>\n";
			echo "</table>\n</form>\n";
		}

	}

}