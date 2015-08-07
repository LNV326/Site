<?php

namespace Controller;

use Controller\AbstractSiteController;

/**
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0 Tested 09/08/2015
 *
 * Refactoring from old site engine version (year 2003). All HTML code transfered to template file.
 *
 */
class SearchControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'SearchTemplate.php';
	
	protected function getData() {
		//Подгрузка необходимостей )
		include "admin/ad_editor/functions/global.php";
		//Определение страницы
		$num=15;
		$p=intval($this->_nfs->input['p']);
		$start=$p*$num;
		$error = false;
		//Определения ID сессии
		if (($this->_nfs->input[searchid]=='') and ($this->_nfs->input['keywords']=='')) {
			$showForm = true;
			$error=TRUE;
		} else if ($this->_nfs->input[searchid]=='') {
			$keywords = trim($this->_SDK->filter_keywords($this->_nfs->input['keywords']));
			if (strlen($keywords)<4) {
				get_rezult('Ключевое слово для поиска должно состоять минимум из 4 символов!');
				get_links();
				$error=TRUE;
			} else {
				$searchid = $this->_SDK->simple_search($keywords);
			}
		} else {
			$searchid = $this->_nfs->input[searchid];
		}
		//Если нет ошибки
		if (!$error) {
			//Получение результатов из таблицы
			$this->_DB->query("SELECT * FROM ibf_search_results WHERE id='".$searchid."'");
			$row = $this->_DB->fetch_row();
			$line_search = array();
			$line_search[topics] = $row['topic_id'];
			$line_search[posts]  = $row['post_id'];
		
			//Модули \ страницы
			foreach( explode( ",", $row['page_id']) as $tid )
			{
				if (preg_match( "/^m_/", $tid ) and ($tid <> "")){
					$tid=intval(str_replace( "m_", "", $tid ) );
					$line_search[modules] .= ",$tid,";
				} else {
					$line_search[pages] .= "$tid,";
				}
			}
			$line_search[pages]  = str_replace( ",,", ",", $line_search[pages] );
			$line_search[modules]  = str_replace( ",,", ",", $line_search[modules] );
		
			$hits_search = array();
			$hits_search[topics] = $row['topic_max'];
			$hits_search[posts]  = $row['post_max'];
			$hits_search[pages]  = $row['page_max'];
			//Где искать?
			if ($this->_nfs->input['search_in']=="") {
				$search_in="topics";
			} else {
				$search_in=$this->_nfs->input['search_in'];
			}
			//Проверка есть ли там что-то
			if ($this->_nfs->input[start]==1) {
				if ($search_in=="topics") {
					if ($hits_search[topics]==0) {
						$search_in="posts";
						if ($hits_search[posts]==0) {
							$search_in="pages";
						}
					}
				}
			}
			//Выборка результатов
			$themes = $this->_SDK->get_search_results($searchid,$search_in,$line_search,$start,$num);
			//Формирование списка страниц
			$pages = pages("index.php?page=search&searchid=".$searchid."&search_in=".$search_in,$hits_search[$search_in],$num);
			//Вывод результатов!
			if (($hits_search[topics]<>0) or ($hits_search[posts]<>0) or ($hits_search[pages]<>0)) {
				if ($search_in <> "topics" and $search_in <> "posts" and $search_in <> "pages") {
					get_rezult('Ничего не найдено!');
					get_links();
				}
			} else {
				get_rezult('Ничего не найдено!');
				get_links();
			}
		}
		return array(
				'showForm' => $showForm,
				'searchId' => $searchid,
				'searchIn' => $search_in,
				'themes' => $themes,
				'pages' => $pages,
				'hits_search' => $hits_search,
				'site_name' => $this->_conf[site_name],
				'site_start_year' => $this->_conf[site_start_year]
		);
	}
}