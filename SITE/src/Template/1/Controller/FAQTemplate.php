<?php switch ($out['page']) { ?>
<?php case 'cat_list' : {?>
<table align=center class=table border=0 cellspacing=0 style='width: 98%'>
	<tr style='height: 10pt'>
		<td style='border: solid windowtext 1.0pt'><p class=normal>
				<img src="<?php echo $lil;?>" border=0><a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a> -=- <?php echo $this->_nfs->unconvert_html($out['cat']['name']);?>
			</p></td>
	</tr>
	<tr style='height: 3pt; background: #121212'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 3pt'>&nbsp;</p></td>
	</tr>
	<tr style='height: 15pt'>
		<td style='border: solid windowtext 1.0pt'><p>
<?php foreach ($out['faqs'] as $index => $row) {?>
<img src="<?php echo $lil;?>" border=0> <a href='#<?php echo $index;?>'><?php echo $this->_nfs->unconvert_html($row['question'])?></a> <br> 
<?php }?>
</p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php foreach ($out['faqs'] as $index => $row) {?>
<tr style='height: 15pt'>
		<td style='border: solid windowtext 1.0pt'><p class=normal>
				<img src="<?php echo $lil;?>" border=0> Вопрос: <a name="<?php echo $index;?>"><?php echo $this->_nfs->unconvert_html($row['question']);?></a>
			</p></td>
	</tr>
	<tr style='height: 15pt'>
		<td style='border: solid windowtext 1.0pt'><p class=normal>Ответ: <?php echo $this->_nfs->unconvert_html($row['answer']);?></p>
			<p class=normal>
				<a href='#top'>Вернуться в начало</a>
			</p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php }?>
<?php if (count($out['faqs']) == 0) {?>
<tr style='height: 15pt'>
		<td style='border: solid windowtext 1.0pt'><p class=normal>Категории не существует, либо в ней нет вопросов.</p></td>
	</tr>
	<tr style='height: 3pt; background: #151515'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 3pt'>&nbsp;</p></td>
	</tr>
<?php }?>	
	<tr style='height: 10pt'>
		<td style='border: solid windowtext 1.0pt'><p class=normal>Просмотров категории: <?php echo $out['cat']['count'];?></p></td>
	</tr>
</table>
<?php break;}?>


<?php case 'forum_help' : {?>
<?php if (isset($out['helpcat'])) {?>
<?php if (!is_null($out['info'])) {?>
				<table class=table align=center border=0 cellspacing=0 style='width:98%'>
				<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal><img src="<?php echo $lil;?>" border=0><a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a> -=- <a href="index.php?page=faq&cat=forum" title="Перейти">Помощь по форуму</a></p></td></tr>
				<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>
				<tr style='background:transparent'><td><p class=normal><b><?php echo $this->_nfs->unconvert_html($out['info']['title']);?></b><br><img width=100% height=1 src='<?php echo $line;?>' vspace=3 border=0><br><?php echo $this->_nfs->unconvert_html($out['info']['description']);?><br><img width=100% height=1 src='<?php echo $line;?>' vspace=3 border=0><br><?php echo $this->_nfs->unconvert_html($out['info']['text']);?></p></td></tr>
				<tr style='height:1pt;background:#151515'><td style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>
				<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal><b><?php echo $this->_conf['site_name'];?></b></p></td></tr>
				</table>
<?php } else {?>
	<p class=normal>Раздел помощи на форуме отсутствует</p>
<?php } ?>
<?php } else {?>
			<table class=table align=center border=0 cellspacing=0 style='width:98%'>
			<tr style='height:10pt'><td style='border:solid windowtext 1.0pt'><p class=normal><img src="<?php echo $lil;?>" border=0><a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a></p></td></tr>
			</table>
			<?php foreach ($out['faqs'] as $i) {?>
				<p class=normal><img src="<?php echo $lil;?>" border=0> <a href='index.php?page=faq&cat=forum&help=<?php echo $i['id']; ?>'><?php echo $this->_nfs->unconvert_html($i['title']); ?></a><br><?php echo $this->_nfs->unconvert_html($i['description']);?></p>
			<?php }?>
<?php } break;}?>


<?php case 'all_cats' : {?>
<table align=center class=table border=0 cellspacing=0 style='width: 98%'>
	<tr style='height: 10pt'>
		<td style='border: solid windowtext 1.0pt'><p class=normal>
				<img src="<?php echo $lil;?>" border=0><a href="index.php?page=faq" title="Перейти">Вопросы и ответы</a>
			</p></td>
	</tr>
	<tr style='height: 3pt; background: #121212'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 3pt'>&nbsp;</p></td>
	</tr>
<?php foreach($out['cats'] as $row) {?>
<tr style='height: 15pt'>
		<td style='width: 100%; border: solid windowtext 1.0pt'>
			<p class=normal>
				Категория: <b><a href='index.php?page=faq&cat=<?php echo $row[id];?>' title=Перейти><?php echo $this->_nfs->unconvert_html($row[name]);?></a></b>
			</p>
		</td>
	</tr>
	<tr style='height: 15pt'>
		<td style='border: solid windowtext 1.0pt'>
			<p class=normal><?php echo $this->_nfs->unconvert_html($row[info]); ?></p>
		</td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php } // end foreach ?>
<?php if ($out['faqFromForum'] == true) {?>
<tr style='height: 15pt'>
		<td style='width: 100%; border: solid windowtext 1.0pt'>
			<p class=normal>
				Категория: <b><a href='index.php?page=faq&cat=forum' title=Перейти>Помощь по использованию форума</a></b>
			</p>
		</td>
	</tr>
	<tr style='height: 15pt'>
		<td style='border: solid windowtext 1.0pt'>
			<p class=normal>Вопросы и ответы по использованию функций на форуме и т.д.</p>
		</td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php }?>
<?php if($out['faqFromForum'] == false and count($out['cats']) == 0) {?>
<tr style='height: 15pt'>
		<td style='width: 100%; border: solid windowtext 1.0pt'>
			<p class=normal>Категорий не существует!</p>
		</td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php } ?>
<tr style='height: 15pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'>
			<p class=normal>
				Всего в базе <b><?php echo $out['faqDBCount']?></b> вопроса(ов) и ответа(ов).
			</p>
		</td>
	</tr>
</table>
<?php } }?>