<?php
namespace Controller;

use Controller\AbstractSiteController;

class FilesControllerDB extends AbstractSiteController {
	
	protected $_templateName = 'FilesTemplate.php';
		
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
				//Авторство
				if ($row[autor]=='')
					$row[autor]='Неизвестен';
				//Проверка рисунка
				if ($row[link]<>'1') {
					$size = null;
					$row[size]="Размер: ".get_size($row[url],$size);
					$row[file_name]=file_name($row[name]);
				} else {
					$row[file_name]=$row[name];
				}
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
		
	//Вывод списка подкатегорий
	private function subcat_list($num,$subcat_id) {
		//Подсчёт кол-ва файлов в категории!
		$this->_DB->query("SELECT count(*) as cnt FROM s_files_db WHERE subcat_id='".$subcat_id."'");
		$filesCount = $this->_DB->fetch_row()['cnt'];
		//округляем и получаем кол-во страниц
		
		$p=intval($this->_nfs->input['p']);
		$start=$p*$num;
		//Получение типа категории
		$this->_DB->query("SELECT s.name as s_name, s.cat_id, c.name as c_name, s.id, s.type FROM s_files_subcat s LEFT JOIN s_files_cat c ON (s.cat_id=c.id) WHERE s.id='".$subcat_id."';");
		$row_subcat = $this->_DB->fetch_row();
		$type = $row_subcat[type];
		
// 		$pages_rul=$this->pages($subcat_id,$pages);
// 		$links=$this->sub_links($subcat);
		$files = $this->subcat_list_for_type($start,$num,$subcat_id,$type);
		return array(
				'page' => 'subcatList',
				'subcatId' => $subcat_id,
				'pageNum' => $p,
				'subcat' => $row_subcat,
				'subcatType' => $type,
				'files' => $files,
				'filesCount' => $filesCount,
				'num' => $num
		);
	}
	
	//Вывод категории
	private function cat_list($cat) {
// 		$links=$this->cat_links($cat);
		$this->_DB->query("SELECT * FROM s_files_cat WHERE id='".$cat."';");
		$catVal = $this->_DB->fetch_row();
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
				'cat' => $catVal,
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