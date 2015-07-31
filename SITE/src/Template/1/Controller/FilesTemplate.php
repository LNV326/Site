<?php switch($out['page']) { ?>
<?php case 'subcatList' : {?>
		<table class=table align=center border=0 cellspacing=0 style='width:98%'>
		<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>
		<tr style='height:3pt;background:#121212'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:3pt'>&nbsp;</p></td></tr>
<?php if ($out['filesCount'] == 0) {?>
			<tr style='height:15pt'>
			<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>В данной категории нет файлов.</p></td>
			</tr>
			<tr style='height:3pt;background:#151515'>
			<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td>
			</tr>
<?php }?>		
<?php if ($out['subcatType'] == 1) {?>
<?php foreach($out['files'] as $row) { //TODO?>
<?php }?>
<?php } else {?>
<?php foreach($out['files'] as $row) {?>
				<tr style='height:15pt'>
				<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>Закачек: <?php echo $row['count'];?></p></td>
				<td style='width:48%;border:solid windowtext 1.0pt'><p align=center><b><a href='/download.php?go=<?php echo $row[id];?>' title=Скачать><?php echo $this->_nfs->unconvert_html($row[file_name]);?></a></b></p></td>
				<td style='width:25%;border:solid windowtext 1.0pt'><p align=center><?php echo $row[size];?></p></td>
				</tr>
				<tr style='height:15pt'>
				<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal><?php echo $this->_nfs->unconvert_html($row[description]);?></p></td>
				</tr>
				<tr style='height:1pt;background:#151515'>
				<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td>
				</tr>
<?php }?>
<?php }?>
		<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><div align='left' style='float:left'><p class=normal>".$pages_rul."</p></div><div align='right'><p class=normal style='text-align:right'>Порядок: 
		<?php
		//Ссылки для сортировки
		$l1n='123';
		$lan='ABC';
		$l1='<a href="index.php?page=files&subcat='.$out['subcatId'].'&p='.$out['pageNum'].'&sort=id">'.$l1n.'</a>';
		$la='<a href="index.php?page=files&subcat='.$out['subcatId'].'&p='.$out['pageNum'].'&sort=name">'.$lan.'</a>';
		if ($this->_nfs->input[sort]=='name') {?>
			<b><?php echo $l1;?></b> / <font style="color: orange"><?php echo $lan;?></font>
		<?php } else {?>
			<font style="color: orange"><?php echo $l1n;?></font> / <b><?php echo $la;?></b>
		<?php }?>
		</p></div></td></tr>
		</table>
<?php break; }?>


<?php case 'catList' : {?>
<table class=table align=center border=0 cellspacing=0 style='width: 98%'>
	<tr style='height: 10pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal>".$links."</p></td>
	</tr>
	<tr style='height: 3pt; background: #121212'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 3pt'>&nbsp;</p></td>
	</tr>
<?php
			// Main sub-categorie
			foreach ( $out ['subcats'] as $row ) {
				if ($row ['type'] == 1)
					continue;
				?>
			<tr style='height: 15pt'>
		<td colspan=2 style='width: 80%; border: solid windowtext 1.0pt'><p class=normal>
				Архив: <b><a href='index.php?page=files&subcat=<?php echo $row[id];?>' title=Перейти><?php echo $this->_nfs->unconvert_html($row['name']);?></a></b>
			</p></td>
		<td colspan=1 style='width: 20%; border: solid windowtext 1.0pt'><p align=center>Файлов: <?php echo $row[files_cnt];?></p></td>
	</tr>
	<tr style='height: 15pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal><?php echo $this->_nfs->unconvert_html($row[info]);?></p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php }?>		
<?php
			// Other sub-categories
			foreach ( $out ['subcats'] as $row ) {
				if ($row ['type'] == 0)
					continue;
				?>
<?php if (!isset($vse)) { $vse=1;?>
				<tr style='height: 15pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p align=center>Другие файлы</p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p style='font-size: 1pt'>&nbsp;</p></td>
	</tr>				
<?php }?>
			<tr style='height: 15pt'>
		<td colspan=2 style='width: 80%; border: solid windowtext 1.0pt'><p class=normal>
				Архив: <b><a href='index.php?page=files&subcat=<?php echo $row[id];?>' title=Перейти><?php echo $this->_nfs->unconvert_html($row[name]);?></a></b>
			</p></td>
		<td colspan=1 style='width: 20%; border: solid windowtext 1.0pt'><p align=center>Файлов: <?php echo $row[files_cnt];?></p></td>
	</tr>
	<tr style='height: 15pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal><?php echo $this->_nfs->unconvert_html($row[info]);?></p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php }?>
<?php if ($out['filesCount'] == 0) {?>				
			<tr style='height: 15pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal>В данной категории нет файлов.</p></td>
	</tr>
<?php } else {?>
			<tr style='height: 15pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal>
				Всего в данной категории <b><?php echo $out['filesCount']?></b> файл(а/ов).
			</p></td>
	</tr>
<?php }?>
</table>
<?php break; } // end of catList?>


<?php case 'allCats' : {?>
<table align=center class=table border=0 cellspacing=0 style='width: 98%'>
	<tr style='height: 10pt'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal>
				<img src="<?php echo $lil;?>" border=0> <a href="index.php?page=files" title="Перейти">Файловый архив</a>
			</p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td colspan=3 style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php foreach ($out['cats'] as $row) {?>
			<tr style='height: 15pt'>
		<td colspan=2 style='border: solid windowtext 1.0pt'><p class=normal>
				Архив: <b><a href='index.php?page=files&cat=<?php echo $row[id];?>' title=Перейти><?php echo $row[name];?></a></b>
			</p></td>
	</tr>
	<tr style='height: 15pt'>
		<td colspan=2 style='border: solid windowtext 1.0pt'><p class=normal><?php echo $this->_nfs->unconvert_html($row[info]);?></p></td>
	</tr>
	<tr style='height: 1pt; background: #151515'>
		<td colspan=2 style='border: solid windowtext 1.0pt'><p class=normal style='font-size: 1pt'>&nbsp;</p></td>
	</tr>
<?php }?>
		<tr style='height: 15pt'>
		<td colspan=2 style='border: solid windowtext 1.0pt'><p class=normal>
				Всего в файловом архиве сайта <b><?php echo $out['subcatCount'];?></b> подкатегорий и <b><?php echo $out['filesCount'];?></b> файла(ов).
			</p></td>
	</tr>
</table>
<?php }}?>
