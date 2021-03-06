<?php
namespace Controller;

use Controller\AbstractSiteController;

class FilesController extends AbstractSiteController {
	
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
		$repo = $this->_em->getRepository('Entity\EntitySFilesCat');
		$cat_row = $repo->find($ids);
		if (!is_null($cat_row)) {
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' <a href="index.php?page=files&cat='.$cat_row->getId().'" title="Перейти">'.$cat_row->getName().'</a>';
		}else{
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' Ошибка';
		}
		return $links;
	}
	
	//Формирования нафигационной ссылки
	private function sub_links($ids) { 
		$nav='<img src="'.$lil.'" border=0> ';
		$repo = $this->_em->getRepository('Entity\EntitySFilesSubcat');
		$row = $repo->find($ids); // TODO Need to join Category here
		if (!is_null($row)) {
			$cat = $row->getCategoryVal();
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' <a href="index.php?page=files&cat='.$cat->getId().'" title="Перейти">'.$cat->getName().'</a> '.$nav.' <a href="index.php?page=files&subcat='.$row->getId().'" title="Перейти">'.$row->getName().'</a>';
		}else{
			$links=$nav.'<a href="index.php?page=files" title="Перейти">Файловый архив</a> '.$nav.' <a href="index.php?page=files" title="Перейти">Ошибка</a>';
		}
		return $links;
	}
	
	//Вывод подкатегории
	private function subcat_list_for_type($sp,$ep,$scat,$typ) { 
		$repo = $this->_em->getRepository('Entity\EntitySFilesDb');
		if ($typ==1) {
			if ($this->_nfs->input[sort]=='name') {
				$sort_type = array('name' => 'ASC');
			} else {
				$sort_type = array('id' => 'DESC');
			}
			$files = $repo->findBy(array('subcatId' => $scat), $sort_type, $ep, $sp);
			foreach ($files as $row) {
				$el_page+=1;
				//Авторство
				if ($row->getAutor()==''){
					$autor='Неизвестен';
				} else {
					$autor=$row->getAutor();
				}
				//Проверка рисунка
				if ($row->getLink()<>'1') {
					$img = $row->getImg();
					if (($img == '') or (!file_exists($this->_conf[images_path].$img))) {
						$img = '/style/'.$this->_style_id.'/img/no_image.gif';
					}
					if ($row->getImgBig()<>'') {
						$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$row->getImgBig()."' target='_blank'>Увеличить</a></p>";
					} else {
						$linktobig="";
					}
				} else {
					$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$row->getImgBig()."' target='_blank'>Увеличить</a></p>";
				}
				echo "<tr style='height:15pt'>\n";
				echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>Закачек: ".$row->getCount()."</p></td>\n";
				if ($row->getLink()<>'1') {
					$size = null;
					$size="Размер: ".get_size($row->getUrl(),$size);
					$file_name=file_name($row->getName());
					echo "<td style='width:48%;border:solid windowtext 1.0pt'><p align=center><b><a href='/download.php?go=".$row->getId()."' title=Скачать>".$this->_nfs->unconvert_html($file_name)."</a></b></p></td>\n";
					echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>".$size."</p></td>\n";
				} else {
					$file_name=$row->getName();
					echo "<td style='width:73%;border:solid windowtext 1.0pt' colspan=2><p align=center><b><a href='/download.php?go=".$row->getId()."' title=Скачать>".$this->_nfs->unconvert_html($file_name)."</a></b></p></td>\n";
				}
				echo "</tr>\n";
				echo "<tr style='height:15pt'>\n";
				echo "<td style='border:solid windowtext 1.0pt'><p class=normal><img src='http://images.".$this->_conf[site_url]."/".$img."' border=0></p>".$linktobig."</td>\n";
				echo "<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row->getDescription())."<br>Автор: <b>".$this->_nfs->unconvert_html($autor)."</b></p></td>\n";
				echo "</tr>\n";
				echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
			}
		} else {
			$files = $repo->findBy(array('subcatId' => $scat), array('id' => 'DESC'), $ep, $sp);
			foreach ($files as $row) {
				$el_page+=1;
				//Ссылка или файл
				if ($row->getLink()<>'1') {
					$size = null;
					$size="Размер: ".get_size($row->getUrl(),$size);
					$file_name=file_name($row->getName());
				} else {
					$file_name=$row->getName();
					$size="Ссылка";
				}
				echo "<tr style='height:15pt'>\n";
				echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>Закачек: ".$row->getCount()."</p></td>\n";
				echo "<td style='width:48%;border:solid windowtext 1.0pt'><p align=center><b><a href='/download.php?go=".$row->getId()."' title=Скачать>".$this->_nfs->unconvert_html($file_name)."</a></b></p></td>\n";
				echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center>".$size."</p></td>\n";
				echo "</tr>\n";
				echo "<tr style='height:15pt'>\n";
				echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row->getDescription())."</p></td>\n";
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
		$repo = $this->_em->getRepository('Entity\EntitySFilesSubcat');
		$subrez = $repo->findBy(array('catId' => $cat, 'type' => $type), array('poz' => 'ASC'));
		$vse = null;
		foreach ($subrez as $row) {
			if (($vse<>1) and ($type==1)){
				echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p align=center>Другие файлы</p></td></tr>\n";
				echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:1pt'>&nbsp;</p></td></tr>\n";
				$vse=1;
			}
			echo "<tr style='height:15pt'>\n";
			echo "<td colspan=2 style='width:80%;border:solid windowtext 1.0pt'><p class=normal>Архив: <b><a href='index.php?page=files&subcat=".$row->getId()."' title=Перейти>".$this->_nfs->unconvert_html($row->getName())."</a></b></p></td>\n";
			echo "<td colspan=1 style='width:20%;border:solid windowtext 1.0pt'><p align=center>Файлов: ".$row->getFilesCnt()."</p></td>\n";
			echo "</tr>\n";
			echo "<tr style='height:15pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row->getInfo())."</p></td></tr>\n";
			echo "<tr style='height:1pt;background:#151515'><td colspan=3 style='border:solid windowtext 1.0pt'><p style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		}
	}
	
	//Вывод списка подкатегорий
	private function subcat_list($num,$subcat) {
		//Получение типа категории
		$repo = $this->_em->getRepository('Entity\EntitySFilesSubcat');
		$row_subcat = $repo->find($subcat);
		//Подсчёт кол-ва файлов в категории!
		$el = count($row_subcat->getFilesVal());
		$type = $row_subcat->getType();
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
		$links = $this->cat_links($cat);
		echo "<table class=table align=center border=0 cellspacing=0 style='width:98%'>\n";
		echo "<tr style='height:10pt'><td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal>".$links."</p></td></tr>\n";
		echo "<tr style='height:3pt;background:#121212'>\n";
		echo "<td colspan=3 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:3pt'>&nbsp;</p></td>\n";
		echo "</tr>\n";
		//Строка с id подкатегорий в категории
		$repo = $this->_em->getRepository('Entity\EntitySFilesSubcat');
		$subcats = $repo->findBy(array('catId' => $cat), array('poz' => 'ASC'));
		foreach ($subcats as $row) {
			//Количество файлов в категории
			$all_counts += count($row->getFilesVal());
		}
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
		$repo = $this->_em->getRepository('Entity\EntitySFilesCat');
		$cats = $repo->getAllSortedByPosition();
		//Подсчёт категорий
		foreach ($cats as $row) {
			echo "<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Архив: <b><a href='index.php?page=files&cat=".$row->getId()."' title=Перейти>".$row->getName()."</a></b></p></td></tr>\n";
			echo "<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>".$this->_nfs->unconvert_html($row->getInfo())."</p></td></tr>\n";
			echo "<tr style='height:1pt;background:#151515'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		}
		$repoSC = $this->_em->getRepository('Entity\EntitySFilesSubcat');
		$repoDB = $this->_em->getRepository('Entity\EntitySFilesDb');
		$cnt_subcats = array('count' => $repoSC->getCount());
		$cnt_files = array('count' => $repoDB->getCount());
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