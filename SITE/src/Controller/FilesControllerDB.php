<?php
namespace Controller;

use Controller\AbstractSiteController;

class FilesControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'FilesTemplate.php';
	
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
		$files = array();
		if ($typ==1) {
			if ($this->_nfs->input[sort]=='name') {
				$sort_type='name ASC';
			} else {
				$sort_type='id DESC';
			}
			$this->_DB->query("SELECT * FROM s_files_db WHERE subcat_id=".$scat." ORDER BY ".$sort_type." LIMIT $sp , $ep");			
			while ($row = $this->_DB->fetch_row()) {
				// TODO
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
				$files[] = $row;
			}
		} else {
			$this->_DB->query("SELECT * FROM s_files_db WHERE subcat_id=".$scat." ORDER BY id DESC LIMIT $sp , $ep");
			while ($row = $this->_DB->fetch_row()) {
				//Ссылка или файл
				if ($row[link]<>'1') {
					$size = null;
					$row[size]="Размер: ".get_size($row[url],$size);
					$row[file_name]=file_name($row[name]);
				} else {
					$row[file_name]=$row[name];
					$row[size]="Ссылка";
				}
				$files[] = $row;
			}
		}
		return $files;
	}
	
// 	//Вывод списка подкатегорий по типам (0-наверху/1-внизу)
// 	private function cat_subcat_list($cat,$type) { 
// 		$subrez=$this->_DB->query("SELECT * FROM s_files_subcat WHERE cat_id='".$cat."' and type='".$type."' ORDER BY poz ASC");
// 		while ($row = $this->_DB->fetch_row($subrez)) {
// 			$vse = null;
// 			if (($vse<>1) and ($type==1)){
// 				echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p align=center>Другие файлы</p></td></tr>\n";
// 				echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:1pt'>&nbsp;</p></td></tr>\n";
// 				$vse=1;
// 			}
// 			echo "<tr style='height:15pt'>\n";
// 			echo "<td colspan=2 style='width:80%;border:solid windowtext 1.0pt'><p class=normal>Архив: <b><a href='index.php?page=files&subcat=".$row[id]."' title=Перейти>".$this->_nfs->unconvert_html($row[name])."</a></b></p></td>\n";
// 			echo "<td colspan=1 style='width:20%;border:solid windowtext 1.0pt'><p align=center>Файлов: ".$row[files_cnt]."</p></td>\n";
// 			echo "</tr>\n";
// 			echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row[info])."</p></td></tr>\n";
// 			echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:1pt'>&nbsp;</p></td></tr>\n";
// 		}
// 	}
	
	//Вывод списка подкатегорий
	private function subcat_list($num,$subcat) {
		//Подсчёт кол-ва файлов в категории!
		$this->_DB->query("SELECT count(*) FROM s_files_db WHERE subcat_id='".$subcat."'");
		$filesCount = $this->_DB->fetch_row()['count'];
		//округляем и получаем кол-во страниц
		$pages=ceil($filesCount/$num);
		$p=intval($this->_nfs->input['p']);
		$start=$p*$num;
		//Получение типа категории
		$this->_DB->query("SELECT * FROM s_files_subcat WHERE id='".$subcat."';");
		$row_subcat = $this->_DB->fetch_row();
		$type = $row_subcat[type];
		
		$pages_rul=$this->pages($subcat,$pages);
		$links=$this->sub_links($subcat);
		$files = $this->subcat_list_for_type($start,$num,$subcat,$type);
		return array(
				'page' => 'subcatList',
				'subcatId' => $this->_nfs->input['subcat'],
				'pageNum' => $p,
				'subcatType' => $type,
				'files' => $files,
				'filesCount' => $filesCount
		);
	}
	
	//Вывод категории
	private function cat_list($cat) {
		$links=$this->cat_links($cat);

		//Строка с id подкатегорий в категории
		$line="-1";
		$subcats = array();
		$this->_DB->query("SELECT * FROM s_files_subcat WHERE cat_id='".$cat."' ORDER BY poz ASC");
		while ($row = $this->_DB->fetch_row()) {
			$line.=",".$row[id];
			$subcats[] = $row;
		}
		//Количество файлов в категории
		$this->_DB->query("SELECT count(id) as count FROM s_files_db WHERE subcat_id IN(".$line.");");
		$row = $this->_DB->fetch_row();
		
		return array(
				'page' => 'catList',
				'subcats' => $subcats,
				'filesCount' => isset($row[count]) ? $row[count] : 0
		);
	}
	
	//Вывод списка категорий
	private function all_cat() {
		$this->_DB->query("SELECT * FROM s_files_cat ORDER BY poz ASC");
		//Подсчёт категорий
		$cats = array();
		while ($row = $this->_DB->fetch_row()) {
			$cats[] = $row;
		}
		$this->_DB->query("SELECT count(id) as count FROM s_files_subcat;");
		$cnt_subcats = $this->_DB->fetch_row();
		$this->_DB->query("SELECT count(id) as count FROM s_files_db;");
		$cnt_files = $this->_DB->fetch_row();
		return array(
				'page' => 'allCats',
				'subcatCount' => $cnt_subcats[count],
				'filesCount' => $cnt_files[count],
				'cats' => $cats
		);
	}
	

	protected function getData() {
		echo '<div class="info" style="margin:10px 10px 0px 10px">Данная версия файлового архива более обновляться не будет.<br>Новая версия файлового архива находится по адресу: <a href="http://files.nfsko.ru" alt="Файловый архив по играм Need For Speed">http://files.nfsko.ru</a></div>';
		//Тело самой программы
		if (isset($this->_nfs->input['subcat']) and ($this->_nfs->input['subcat'] <> '')){
			$subcat=intval($this->_nfs->input['subcat']);
			return $this->subcat_list(10,$subcat);
		} else if (isset($this->_nfs->input['cat']) and ($this->_nfs->input['cat'] <> '')){
			$cat=intval($this->_nfs->input['cat']);
			return $this->cat_list($cat);
		} else {
			return $this->all_cat();
		}
	}

	

}