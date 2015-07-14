<?php
namespace Controller;

use Controller\AbstractSiteController;

class FilesControllerDB extends AbstractSiteController {
	
	private function pages($subcat,$pages) {
		if (isset($this->_nfs->input['subcat'])){
			$more='&subcat='.$this->_nfs->input['subcat'];
		}
		if ($this->_nfs->input[sort]=='name') {
			$sort_type='&sort=name';
		}
		if ($pages>1){
			$pages_rul=$this->_lang[page].': [';
			for ($i=0; $i<$pages; $i++){
				$p=$i+1;
				if ($i == $this->_nfs->input['p']){
					$text='<font style="color: orange">'.$p.'</font>';
				} else{
					$text='<b>'.$p.'</b>';
				}
				if ($i>0) {
					$pages_rul.='|<a href="index.php?page=files'.$more.'&p='.$i.$sort_type.'">'.$text.'</a>';
				} else {
					$pages_rul.='<a href="index.php?page=files'.$more.$sort_type.'">'.$text.'</a>';
				}
			}
			$pages_rul.=']';
		} else {
			$pages_rul.='<b>'.$this->_conf[site_name].'</b>';
		}
		return $pages_rul;
	}
	
	//Формирования нафигационной ссылки
	private function cat_links($ids) {
		$nav='<img src="'.$lil.'" border=0> ';
		$this->_DB->query("SELECT * FROM s_files_cat WHERE id='".$ids."';");
		$cat_row = $this->_DB->fetch_row();
		if (isset($cat_row[id])){
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' <a href="index.php?page=files&cat='.$cat_row[id].'" title="Перейти">'.$cat_row[name].'</a>';
		}else{
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' Ошибка';
		}
		return $links;
	}
	
	//Формирования нафигационной ссылки
	private function sub_links($ids) { 
		$nav='<img src="'.$lil.'" border=0> ';
		$this->_DB->query("SELECT s.name as s_name, s.cat_id, c.name as c_name FROM s_files_subcat s LEFT JOIN s_files_cat c ON (s.cat_id=c.id) WHERE s.id='".$ids."';");
		$row = $this->_DB->fetch_row();
		if (isset($row[c_name])){
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' <a href="index.php?page=files&cat='.$row[cat_id].'" title="Перейти">'.$row[c_name].'</a> '.$nav.' <a href="index.php?page=files&subcat='.$ids.'" title="Перейти">'.$row[s_name].'</a>';
		}else{
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' <a href="index.php?page=files" title="Перейти">Ошибка</a>';
		}
		return $links;
	}
	
	//Вывод подкатегории
	private function subcat_list_for_type($sp,$ep,$scat,$typ) { 
		if ($typ==1) {
			if ($this->_nfs->input[sort]=='name') {
				$sort_type='name ASC';
			} else {
				$sort_type='id DESC';
			}
			$this->_DB->query("SELECT * FROM s_files_db WHERE subcat_id=".$scat." ORDER BY ".$sort_type." LIMIT $sp , $ep");
			while ($row = $this->_DB->fetch_row()) {
				$el_page+=1;
				//Авторство
				if ($row[autor]==''){
					$autor='Неизвестен';
				} else {
					$autor=$row[autor];
				}
				//Проверка рисунка
				if ($row[link]<>'1') {
					if (($row[img]=='') or (!file_exists($this->_conf[images_path].$row[img]))) {
						$row[img]='/style/'.$this->_style_id.'/img/no_image.gif';
					}
					if ($row[img_big]<>'') {
						$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$row[img_big]."' target='_blank'>Увеличить</a></p>";
					} else {
						$linktobig="";
					}
				} else {
					$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$row[img_big]."' target='_blank'>Увеличить</a></p>";
				}
				echo "<tr style='height:15pt'>\n";
				echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>Закачек: ".$row[count]."</p></td>\n";
				if ($row[link]<>'1') {
					$size = null;
					$size="Размер: ".get_size($row[url],$size);
					$file_name=file_name($row[name]);
					echo "<td style='width:48%;border:solid windowtext 1.0pt'><p align=center><b><a href='/download.php?go=".$row[id]."' title=Скачать>".$this->_nfs->unconvert_html($file_name)."</a></b></p></td>\n";
					echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>".$size."</p></td>\n";
				} else {
					$file_name=$row[name];
					echo "<td style='width:73%;border:solid windowtext 1.0pt' colspan=2><p align=center><b><a href='/download.php?go=".$row[id]."' title=Скачать>".$this->_nfs->unconvert_html($file_name)."</a></b></p></td>\n";
				}
				echo "</tr>\n";
				echo "<tr style='height:15pt'>\n";
				echo "<td style='border:solid windowtext 1.0pt'><p class=normal><img src='http://images.".$this->_conf[site_url]."/".$row[img]."' border=0></p>".$linktobig."</td>\n";
				echo "<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row[description])."<br>Автор: <b>".$this->_nfs->unconvert_html($autor)."</b></p></td>\n";
				echo "</tr>\n";
				echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
			}
		} else {
			$this->_DB->query("SELECT * FROM s_files_db WHERE subcat_id=".$scat." ORDER BY id DESC LIMIT $sp , $ep");
			while ($row = $this->_DB->fetch_row()) {
				$el_page+=1;
				//Ссылка или файл
				if ($row[link]<>'1') {
					$size = null;
					$size="Размер: ".get_size($row[url],$size);
					$file_name=file_name($row[name]);
				} else {
					$file_name=$row[name];
					$size="Ссылка";
				}
				echo "<tr style='height:15pt'>\n";
				echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>Закачек: ".$row[count]."</p></td>\n";
				echo "<td style='width:48%;border:solid windowtext 1.0pt'><p align=center><b><a href='/download.php?go=".$row[id]."' title=Скачать>".$this->_nfs->unconvert_html($file_name)."</a></b></p></td>\n";
				echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>".$size."</p></td>\n";
				echo "</tr>\n";
				echo "<tr style='height:15pt'>\n";
				echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row[description])."</p></td>\n";
				echo "</tr>\n";
				echo "<tr style='height:1pt;background:#151515'>\n";
				echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td>\n";
				echo "</tr>\n";
			}
		}
		if (($el_page == 0)) {
			echo "<tr style='height:15pt'>\n";
			echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>В данной категории нет файлов.</p></td>\n";
			echo "</tr>\n";
			echo "<tr style='height:3pt;background:#151515'>\n";
			echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td>\n";
			echo "</tr>\n";
		}
	}
	
	//Вывод списка подкатегорий по типам (0-наверху/1-внизу)
	private function cat_subcat_list($cat,$type) { 
		$subrez=$this->_DB->query("SELECT * FROM s_files_subcat WHERE cat_id='".$cat."' and type='".$type."' ORDER BY poz ASC");
		while ($row = $this->_DB->fetch_row($subrez)) {
			$vse = null;
			if (($vse<>1) and ($type==1)){
				echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p align=center>Другие файлы</p></td></tr>\n";
				echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:1pt'>&nbsp;</p></td></tr>\n";
				$vse=1;
			}
			echo "<tr style='height:15pt'>\n";
			echo "<td colspan=2 style='width:80%;border:solid windowtext 1.0pt'><p class=normal>Архив: <b><a href='index.php?page=files&subcat=".$row[id]."' title=Перейти>".$this->_nfs->unconvert_html($row[name])."</a></b></p></td>\n";
			echo "<td colspan=1 style='width:20%;border:solid windowtext 1.0pt'><p align=center>Файлов: ".$row[files_cnt]."</p></td>\n";
			echo "</tr>\n";
			echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row[info])."</p></td></tr>\n";
			echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		}
	}
	
	//Вывод списка подкатегорий
	private function subcat_list($num,$subcat) {
		//Подсчёт кол-ва файлов в категории!
		$this->_DB->query("SELECT id FROM s_files_db WHERE subcat_id='".$subcat."'");
		$el=$this->_DB->get_num_rows();
		//Получение типа категории
		$this->_DB->query("SELECT * FROM s_files_subcat WHERE id='".$subcat."';");
		$row_subcat = $this->_DB->fetch_row();
		$type = $row_subcat[type];
		//округляем и получаем кол-во страниц
		$pages=ceil($el/$num);
		$p=intval($this->_nfs->input['p']);
		$start=$p*$num;
		$pages_rul=$this->pages($subcat,$pages);
		$links=$this->sub_links($subcat);
		echo "<table class=table align=center border=0 cellspacing=0 style='width:98%'>\n";
		echo "<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
		echo "<tr style='height:3pt;background:#121212'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
		$this->subcat_list_for_type($start,$num,$subcat,$type);
		//Ссылки для сортировки
		$l1n='123';
		$lan='ABC';
		$l1='<a href="index.php?page=files&subcat='.$this->_nfs->input['subcat'].'&p='.intval($this->_nfs->input['p']).'&sort=id">'.$l1n.'</a>';
		$la='<a href="index.php?page=files&subcat='.$this->_nfs->input['subcat'].'&p='.intval($this->_nfs->input['p']).'&sort=name">'.$lan.'</a>';
		if ($this->_nfs->input[sort]=='name') {
			$sort_text='<b>'.$l1.'</b> / <font style="color: orange">'.$lan.'</font>';
		} else {
			$sort_text='<font style="color: orange">'.$l1n.'</font> / <b>'.$la.'</b>';
		}
		echo "<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><div align='left' style='float:left'><p class=normal>".$pages_rul."</p></div><div align='right'><p class=normal style='text-align:right'>Порядок: ".$sort_text."</p></div></td></tr>\n";
		echo "</table>\n";
	}
	
	//Вывод категории
	private function cat_list($cat) {
		$links=$this->cat_links($cat);
		echo "<table class=table align=center border=0 cellspacing=0 style='width:98%'>\n";
		echo "<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
		echo "<tr style='height:3pt;background:#121212'>\n";
		echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td>\n";
		echo "</tr>\n";
		//Строка с id подкатегорий в категории
		$line="-1";
		$this->_DB->query("SELECT id FROM s_files_subcat WHERE cat_id='".$cat."' ORDER BY poz ASC");
		while ($row = $this->_DB->fetch_row()) {
			$line.=",".$row[id];
		}
		//Количество файлов в категории
		$this->_DB->query("SELECT count(id) as count FROM s_files_db WHERE subcat_id IN(".$line.");");
		$row = $this->_DB->fetch_row();
		$all_counts=$row[count];
		//Вывод подкатегорий по типам
		$this->cat_subcat_list($cat,0);
		$this->cat_subcat_list($cat,1);
		if (($all_counts == '')){
			$all_counts=0;
			echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>В данной категории нет файлов.</p></td></tr>\n";
		}else{
			echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>Всего в данной категории <b>".$all_counts."</b> файл(а/ов).</p></td></tr>\n";
		}
		echo "</table>\n";
	}
	
	//Вывод списка категорий
	private function all_cat() {
		$nav='<img src="'.$lil.'" border=0> ';
		$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a>';
		echo "<table align=center class=table border=0 cellspacing=0 style='width:98%'>\n";
		echo "<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
		echo "<tr style='height:1pt;background:#151515'>\n";
		echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td>\n";
		echo "</tr>\n";
		$this->_DB->query("SELECT * FROM s_files_cat ORDER BY poz ASC");
		//Подсчёт категорий
		while ($row = $this->_DB->fetch_row()) {
			echo "<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Архив: <b><a href='index.php?page=files&cat=".$row[id]."' title=Перейти>".$row[name]."</a></b></p></td></tr>\n";
			echo "<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row[info])."</p></td></tr>\n";
			echo "<tr style='height:1pt;background:#151515'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		}
		$this->_DB->query("SELECT count(id) as count FROM s_files_subcat;");
		$cnt_subcats = $this->_DB->fetch_row();
		$this->_DB->query("SELECT count(id) as count FROM s_files_db;");
		$cnt_files = $this->_DB->fetch_row();
		echo "<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Всего в файловом архиве сайта <b>".$cnt_subcats[count]."</b> подкатегорий и <b>".$cnt_files[count]."</b> файла(ов).</p></td></tr>\n";
		echo "</table>\n";
	}
	

	protected function getData() {
		echo '<div class="info" style="margin:10px 10px 0px 10px">Данная версия файлового архива более обновляться не будет.<br>Новая версия файлового архива находится по адресу: <a href="http://files.nfsko.ru" alt="Файловый архив по играм Need For Speed">http://files.nfsko.ru</a></div>';
		//Тело самой программы
		if (isset($this->_nfs->input['subcat']) and ($this->_nfs->input['subcat'] <> '')){
			$subcat=intval($this->_nfs->input['subcat']);
			$this->subcat_list(10,$subcat);
		} else if (isset($this->_nfs->input['cat']) and ($this->_nfs->input['cat'] <> '')){
			$cat=intval($this->_nfs->input['cat']);
			$this->cat_list($cat);
		} else {
			$this->all_cat();
		}
	}

	

}