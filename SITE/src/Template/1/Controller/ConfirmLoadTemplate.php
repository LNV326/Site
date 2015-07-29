
<script language='JavaScript' type='text/javascript'>
function view_url() {
prompt ('Ссылка к файлу <?php echo $out['filename'];?> (<?php echo $out['filesize'];?>):','<?php echo $out['filelink'];?>')
}
</script>	
<form action='download.php?go=<? echo $out['fid']; ?>' method='post' name='Confirm' style='margin:3pt'>
<input type='hidden' name='check' value='1'>
<table align=center class=table border=0 cellspacing=0 style='width:98%'>
<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal><b><?php echo $out['filename'];?></b></p></td></tr>
<tr style='height:15pt'>
<?php if ( isset($out['file']['img']) ) {?>
	<td style='border:solid windowtext 1.0pt'><p class=normal>Описание:<br><?php echo $this->_nfs->unconvert_html($out['file']['description']);?><br>
<?php if ($out['file']['autor']<>'') {?>
				<br>Авторы: <b><?php echo $out['file']['autor'];?></b>
<?php }?>
<?php if ($out['file']['link']==0) {?>
				<br>Размер файла: <?php echo $out['filesize']?>
<?php }?>
			<br>Скачиваний: <b><?php echo $out['file']['count'];?></b>.</p></td>
			<?php 
			
			if ($out['file'][link]<>'1') {
				if (($out['file'][img]=='') or (!file_exists($this->_conf[images_path].$out['file'][img]))) {
					$out['file'][img]='/style/'.$this->_style_id.'/img/no_image.gif';
				}
				if ($out['file'][img_big]<>'') {
					$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$out['file'][img_big]."' target='_blank'>Увеличить</a></p>";
				} else {
					$linktobig="";
				}
			} else {
				$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$out['file'][img_big]."' target='_blank'>Увеличить</a></p>";
			}
			
			?>
			
			<td style='border:solid windowtext 1.0pt'><p align=center><img src='http://images.<?php echo $this->_conf[site_url].'/'.$out['file']['img']?>' border=0></p><?php echo $linktobig;?></td>
<?php } else {?>
			<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Описание:<br><?php echo $this->_nfs->unconvert_html($out['file']['description']);?><br>
<?php if ($out['file']['autor']<>'') {?>
				<br>Авторы: <b><?php echo $out['file']['autor'];?></b>
<?php }?>
<?php if ($out['file']['link']==0) {?>
				<br>Размер файла: <?php echo $out['filesize']?>
<?php }?>
			<br>Скачиваний: <b><?php echo $out['file']['count'];?></b>.</p></td>
<?php }?>
</tr>
<tr style='height:1pt;background:#151515'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>
<tr style='height:15pt'>
<?php if ($out['file']['link']==0) {?>
			<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Если при скачивании файла у вас возникли какие либо проблемы обратитесь к администрации.</p><p class=normal>Если вы хотите скачать файл менеджером закачки файлов, то для получения ссылки на файл воспользуйтесь кнопкой (Ссылка). Докачка поддерживается!</p></td>
<?php } else {?>	
			<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Этот файл находится не на нашем сервере. Нажав кнопку вы попадёте либо на страницу с которой можно будет загрузить этот файл, либо на сам файл. Если ссылка не работает сообщите об этом администрации!</p><p class=normal>Для получения ссылки на файл или страницу, нажмите на кнопку (Показать ссылку).</p></td>
<?php }?>
</tr>
<tr style='height:1pt;background:#151515'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>
<tr style='height:15pt'>
<?php if ($out['file']['link']==0) {?>
<td style='width:75%;border:solid windowtext 1.0pt'><p class=normal><input type='submit' name='submit' class='forminput' value='Скачать / Download'>  <input type='button' name='viewurl' class='forminput' value='Ссылка / Link' onclick='view_url()'><p class=normal></p></td>
<?php } else {?>		
			<td style='width:75%;border:solid windowtext 1.0pt'><p class=normal><input type='submit' name='submit' class='forminput' value='Перейти по ссылке / Download'> <input type='button' name='viewurl' class='forminput' value='Показать ссылку / Show link' onclick='view_url()'></p></td>
<?php }?>
<td style='width:25%;border:solid windowtext 1.0pt'><p align=center><input name='Button' class=forminput type='button' value='Вернуться назад' onClick='javascript:history.go(-1)'></p></td></tr>
</table></form>