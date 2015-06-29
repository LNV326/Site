<?php

namespace Controller;

use Controller\AbstractSiteController;

class ArticlesController extends AbstractSiteController {
	
	protected $_templateName = '/modules/article.tpl';
	protected $_caching = 0;
	
	protected $page = array();
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		switch ($this->_nfs->input['name']) {
			case 'stat': $this->stats(); break;
			case 'user': $this->user_articles($this->_nfs->input['id']);
			default: $this->get_article($this->_nfs->input['name']);
		}
	}
		
	//Вывод статьи
	private function get_article($name = '') {
		$this->_DB->query("SELECT * FROM s_pages WHERE name='".addslashes($name)."';");
		if ( !$this->page = $this->_DB->fetch_row() ){
			$this->error('Ошибка 404: Страница на найдена!');
		} else {
			$this->page[counts]=$this->page[counts]+1;
			//Убираем лишнее
			$this->page[html_page]=trim(stripslashes($this->page[html_page]))."\n";
			//Ставим всем внешним ссылкам <noindex> и rel="nofollow"
			$this->page[html_page]=preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU',
			'<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $this->page[html_page]);
			//Sape Context
			if ($sape_show) $this->page[html_page]=$sape_context->replace_in_text_segment($this->page[html_page]);
			//Поиск страницы, в которую вложена
			if ($this->page['root_page']<>'') {
				$this->_DB->query("SELECT title FROM s_pages WHERE name='".$this->page['root_page']."';");
				$root_page = $this->_DB->fetch_row();
				$this->page['root_page_title'] = $root_page['title'];
			}
			//Кто автор
			if ($this->page['created_by']>0) {
				$this->_DB->query("SELECT name FROM `forum_members` WHERE id=".$this->page['created_by'].";");
				$userinfo = $this->_DB->fetch_row();
				if (!empty($userinfo['name'])) {
					$this->page['created'] = "<noindex><a href='/forum/index.php?showuser=".$this->page['created_by']."'><b>".$userinfo['name']."</b></a>, ".date_smart($this->page['created'],1)."</noindex>";
				} else {
					$this->page['created'] = "Не указано";
				}
			} else {
				$this->page['created'] = "Нет данных";
			}
			//Вывод
			$this->_smarty->assign('tll', '/style/1/img/tll.gif');
			$this->_smarty->assign('tlr', '/style/1/img/tlr.gif');
			$this->_smarty->assign_by_ref('page', $this->page);
		
			include DIR.'/sources/3rdparty/comments.php';
			$comments=new comments('pages',$this->page['id']);
			echo $comments->Show();
		
			$this->_DB->query("UPDATE s_pages SET counts='".intval($this->page[counts])."' WHERE name='".$this->page['name']."';");	
		}
	}
	
	//Вывод ссылок на все статьи пользователя
	function user_articles($uid = 0) {
		$this->_DB->query("SELECT name FROM `forum_members` WHERE id=".intval($uid).";");
		if (!$userinfo = $this->_DB->fetch_row()) {
			$this->error('Пользователя с таким ID не обнаружено!');
			return;
		} else {
			$this->page['title'] = "Статьи пользователя <a href='/forum/index.php?showuser=".$uid."'><b>".$userinfo['name']."</b></a>";
		}
		//Ищем статьи
		$this->page['html_page'] = "";
		$this->_DB->query("SELECT title,name FROM `s_pages` WHERE created_by=".intval($uid)." ORDER BY created DESC;");
		$count = 0;
		while ($row = $this->_DB->fetch_row()) {
			$this->page['html_page'] .= "<br/><a href='/index.php?page=article&name=".$row['name']."'>".$row['title']."</a>";
			$count++;
		}
		$this->page['html_page'] = "<p class='normal'><b>Всего статей: ".$count."</b><br/>".$this->page['html_page']."</p>";
		//Вывод
		$this->_smarty->assign('tll', '/style/1/img/tll.gif');
		$this->_smarty->assign('tlr', '/style/1/img/tlr.gif');
		$this->_smarty->assign_by_ref('page', $this->page);
	}
	
	//Статистика модуля
	function stats() {
		$this->page['title'] = "Статистика модуля статей";
		$this->page['html_page'] = "";
		//Всего статей
		$this->_DB->query("SELECT count(id) as count FROM `s_pages`;");
		$row = $this->_DB->fetch_row();
		$this->page['html_page'] .= "Всего статей: ".$row['count']."<br/><br/>";
		//Топ 10 авторов
		$this->page['html_page'] .= "<b>Топ 10 авторов:</b><br/>";
// 		$autors = array();
		$this->_DB->query("SELECT p.created_by, u.name, count(p.created_by) as count FROM `s_pages` as p LEFT JOIN `forum_members` as u ON (u.id = p.created_by) WHERE p.created_by<>0 GROUP BY p.created_by ORDER BY count DESC LIMIT 0,10;");
		while ($row = $this->_DB->fetch_row()) {
			$this->page['html_page'] .= "<br/><a href='/forum/index.php?showuser=".$row['created_by']."'><b>".$row['name']."</b></a> (Статей: ".$row['count'].")";
		}
		//Автор не указан
		$this->_DB->query("SELECT count(id) as count FROM `s_pages` WHERE created_by = 0;");
		$row = $this->_DB->fetch_row();
		$this->page['html_page'] .= "<br/>Статей без автора: ".$row['count']."<br/>";
		$this->page['html_page'] = "<p class='normal'>".$this->page['html_page']."</p>";
		//Вывод
		$this->_smarty->assign('tll', '/style/1/img/tll.gif');
		$this->_smarty->assign('tlr', '/style/1/img/tlr.gif');
		$this->_smarty->assign_by_ref('page', $this->page);
	}
	
	private function error($txt) {
		echo $txt;
	}
}