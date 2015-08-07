<?php if ($out['showForm'] == true) { ?>
<form action='index.php?page=search&search_in=topics&start=1'
	method='POST' style='font-size: 8pt; text-align: justify; margin: 0cm'>
	<table align=center width=98% cellspacing=1 cellpadding=1
		style='border-top: 1px solid #555555; border-bottom: 1px solid #555555; border-left: 1px solid #555555; border-right: 1px solid #555555; margin: 3pt'>
		<tr bgcolor='#424242'>
			<td colspan=2><p align=center>Поиск по всему сайту</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#424242'>
			<td colspan=2><p class=normal>Здесь вы сможете найти интерисующую вас
					информация по ключевому слову. Поиск производится в названиях тем,
					теле сообщений форума а так-же в теле и названиях страниц самого
					сайта. Результаты выводятся отдельно:</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#373737'>
			<td width=65%><p>Ключевое слово(а) для поиска (не менее 4 символов):</p></td>
			<td width=35%><input type='text' name='keywords' class=textinput
				size='47' value=''></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#373737'>
			<td colspan=2><p align=center>
					<input type='submit' value='Поиск по сайту' wight='15'
						class=forminput>
				</p></td>
		</tr>
		<tr bgcolor='#212121'>
			<td colspan=2><p style='font-size: 3pt'>&nbsp;</p></td>
		</tr>
		<tr bgcolor='#424242'>
			<td colspan=2><p align=center>© <?php echo $out['site_name']." ".$out['site_start_year']." - ".date(Y).$out['pages'];?></p></td>
		</tr>
	</table>
</form>
<?php }?>
<?php if ($out['isError'] == false) {?>
<?php switch ($out['searchIn']) {?>
<?php case 'topics' : {?>
					<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>
					<tr bgcolor='#424242'><td colspan=4><p class=news align=center>Результаты поиска в темах форума<?php echo $out['pages'];?></p></td></tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<tr bgcolor='#373737'>
					<td colspan=4><p class=news>Результаты поиска по сайту в целом:<br>
				Найдено <?php echo $out[hits_search][topics];?> совпадений в названиях тем форума. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=topics'>показать</a>)<br>
				Найдено <?php echo $out[hits_search][posts];?> совпадений в сообщениях форума. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=posts'>показать</a>)<br>
				Найдено <?php echo $out[hits_search][pages];?> совпадений в страницах сайта. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=pages'>показать</a>)</p></td>
					</tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<tr bgcolor='#424242'>
					<td width='50%'><p class=news>Тема / Автор / Форум:</p></td>
					<td width='10%'><p class=news align=center>Ответов</p></td>
					<td width='15%'><p class=news align=center>Просмотров</p></td>
					<td width='25%'><p class=news align=center>Последний ответ</p></td>
					</tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<?php foreach ($out['themes'] as $row) {?>
						<tr bgcolor='#373737'>
						<td><p class=news>Тема: <a href='/forum/index.php?showtopic=<?php echo $row[tid];?>' target='_blank'><?php echo $row[title];?></a><br>Автор: <a href='/forum/index.php?showuser=<?php echo $row[starter_id];?>' target='_blank'><?php echo $row[starter_name];?></a><br>Форум: <a href='/forum/index.php?showforum=<?php echo $row[forum_id];?>' target='_blank'><?php echo $row[forum_name];?></a></p></td>
						<td><p class=news align=center><?php echo $row[posts];?></p></td>
						<td><p class=news align=center><?php echo $row[views];?></p></td>
						<td><p class=news align=center>Автор: <a href='/forum/index.php?showuser=<?php echo $row[last_poster_id];?>' target='_blank'><?php echo $row[last_poster_name];?></a><br><?php echo $row[last_post];?></p></td>
						</tr>
					<?php }?>
					<?php if (count($out['themes']) == 0) {?>
						<tr bgcolor='#373737'>
						<td colspan=4><p class=news align=center>Ничего не найдено</p></td>
						</tr>
					<?php }?>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<tr bgcolor='#424242'><td colspan=4><p class=news align=center>© <?php echo $out['site_name']." ".$out['site_start_year']." - ".date(Y).$out['pages'];?></p></td></tr>
					</table>
<?php break; } case 'posts' : {?>
					<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>
					<tr bgcolor='#424242'><td colspan=4><p class=news align=center>Результаты поиска в сообщениях форума<?php echo $out['pages'];?></p></td></tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<tr bgcolor='#373737'>
					<td colspan=4><p class=news>Результаты поиска по сайту в целом:<br>
				Найдено <?php echo $out[hits_search][topics];?> совпадений в названиях тем форума. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=topics'>показать</a>)<br>
				Найдено <?php echo $out[hits_search][posts];?> совпадений в сообщениях форума. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=posts'>показать</a>)<br>
				Найдено <?php echo $out[hits_search][pages];?> совпадений в страницах сайта. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=pages'>показать</a>)</p></td>
					</tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<?php foreach($out['themes'] as $row) {?>
						<tr bgcolor='#373737'>
						<td width=70%><p class=news>Автор: <a href='/forum/index.php?showuser=<?php echo $row[starter_id];?>' target='_blank'><?php echo $row[starter_name];?></a></p></td>
						<td width=30%><p class=news align=right>Дата: <?php echo $row[post_date];?></p></td>
						</tr>
						<tr bgcolor='#373737'><td colspan=2><p class=news>Тема: <a href='/forum/index.php?showtopic=<?php echo $row[tid];?>' target='_blank'><b><?php echo $row[title];?></b></a></p><p class=news><?php echo $row[post];?></p></td></tr>
						<tr bgcolor='#373737'>
						<td><p class=news>Форум: <a href='/forum/index.php?showforum=<?php echo $row[fid];?>' target='_blank'><?php echo $row[forum_name];?></a></p></td>
						<td><p class=news align=right><a href='/forum/index.php?showtopic=<?php echo $row[tid];?>&view=findpost&p=<?php echo $row[pid];?>' target='_blank'>Показать сообщение</a></p></td>
						</tr>
						<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<?php }?>
					<?php if (count($out['themes']) == 0) {?>
						<tr bgcolor='#373737'>
						<td colspan=4><p class=news align=center>Ничего не найдено</p></td>
						</tr>
						<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<?php }?>
					<tr bgcolor='#424242'><td colspan=4><p class=news align=center>© <?php echo $out['site_name']." ".$out['site_start_year']." - ".date(Y).$out['pages'];?></p></td></tr>
					</table>
<?php break; } case 'pages' : {?>
<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>
					<tr bgcolor='#424242'><td colspan=4><p class=news align=center>Результаты поиска в страницах сайта</p></td></tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<tr bgcolor='#373737'>
					<td colspan=4><p class=news>Результаты поиска по сайту в целом:<br>
				Найдено <?php echo $out[hits_search][topics];?> совпадений в названиях тем форума. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=topics'>показать</a>)<br>
				Найдено <?php echo $out[hits_search][posts];?> совпадений в сообщениях форума. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=posts'>показать</a>)<br>
				Найдено <?php echo $out[hits_search][pages];?> совпадений в страницах сайта. (<a href='index.php?page=search&searchid=<?php echo $out['searchId'];?>&search_in=pages'>показать</a>)</p></td>
					</tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<tr bgcolor='#424242'>
					<td width='85%'><p class=news>Название страницы сайта</p></td>
					<td width='15%'><p class=news align=center>Просмотров</p></td>
					</tr>
					<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<?php foreach($out['themes'] as $row) {?>
						<tr bgcolor='#373737'>
						<td width=80%><p class=news><a href='index.php?page=<?php echo $row[name];?>' target='_blank'><?php echo $row[title];?></a></p></td>
						<td width=20%><p class=news align=center><?php echo $row[count];?></p></td>
						</tr>
					<?php }?>
					<?php if (count($out['themes']) == 0) {?>
						<tr bgcolor='#373737'>
						<td colspan=4><p class=news align=center>Ничего не найдено</p></td>
						</tr>
						<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>
					<?php }?>
					<tr bgcolor='#424242'><td colspan=4><p class=news align=center>© <?php echo $out['site_name']." ".$out['site_start_year']." - ".date(Y)?></p></td></tr>
					</table>
<?php } }?>
<?php }?>
