<?php

namespace Controller;

use Controller\AbstractSiteController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Template\TemplateEngineAdapter;
use Template\TemplateEngineInterface;

class Article {
	
	private $_id = null;
	private $_name = null;
	public $_page = null;
	
	public function __construct($id, $name, $page) {
		$this->_id = $id;
		$this->_name = $name;
		$this->_page = $page;
	}
	
	public function increaseCounter() {
		global $DB;
		$DB->query("UPDATE s_pages SET counts=counts+1 WHERE id='".$this->_id."';");
	}
	
	static function findByName($name) {
		global $DB;
		$DB->query("SELECT * FROM s_pages WHERE name='".addslashes($name)."';");
		if ( $page = $DB->fetch_row() )
			return new Article($page[id], $page[name], $page);
		return null;
	}
}

class ArticlesControllerDB extends AbstractSiteController {
	
	protected $_caching = 0;
	
	protected $page = array();
	

	// TODO Depricated, delete this
	protected function getData() {}
	
	public function showArticleAction($articleName) {
		global $sape_show, $sape_context;
		
		$this->_templateName = '../../../../src/Template/1/Controller/article.tpl';
		$this->_caching = TemplateEngineInterface::CACHE_MODE_DISABLE;
		$this->_cacheLifetime = TemplateEngineInterface::TIME_EXPIRE_0;
		
		if ($this->_SDK->is_supermod() || $this->_SDK->is_admin()) {
			$this->_templateName = '../../../../src/Template/1/Controller/article_mod.tpl';
		}
		
		try {
			$article = Article::findByName($articleName);
			$article->increaseCounter();
		} catch (\Exception $e) {
			// TODO Not Unique exception
			// TODO NoDatafound exception
		}
		if (is_null($article))
			throw new ResourceNotFoundException('No article found');
		
		$page = $article->_page;
		
		//Убираем лишнее
		$page[html_page]=trim(stripslashes($page[html_page]))."\n";
		//Ставим всем внешним ссылкам <noindex> и rel="nofollow"
		$page[html_page]=preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU',
				'<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $page[html_page]);
		//Sape Context
		if ($sape_show) $page[html_page]=$sape_context->replace_in_text_segment($page[html_page]);
		//Поиск страницы, в которую вложена
		if ($page['root_page']<>'') {
			$this->_DB->query("SELECT title FROM s_pages WHERE name='".$page['root_page']."';");
			$root_page = $this->_DB->fetch_row();
			$page['root_page_title'] = $root_page['title'];
		}

		//Кто автор
		if ($page['created_by']>0) {
			$this->_DB->query("SELECT name FROM `forum_members` WHERE id=".$page['created_by'].";");
			$userinfo = $this->_DB->fetch_row();
			if (!empty($userinfo['name'])) {
				$page['created'] = "<noindex><a href='/forum/index.php?showuser=".$page['created_by']."'><b>".$userinfo['name']."</b></a>, ".date_smart($page['created'],1)."</noindex>";
			} else {
				$page['created'] = "Не указано";
			}
		} else {
			$page['created'] = "Нет данных";
		}
		//Вывод	
		$templateEngine = TemplateEngineAdapter::getInstanceBase($this->_templateName);
		$templateEngine->setCachingMode($this->_caching);
		$templateEngine->setCacheLifetime($this->_cacheLifetime);
		$templateEngine->display( $this->_templateName, array('page' => $page) );
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
// 		$this->_smarty->assign('tll', '/style/1/img/tll.gif');
// 		$this->_smarty->assign('tlr', '/style/1/img/tlr.gif');
		$this->_templateParams['page'] = $this->page;
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
// 		$this->_smarty->assign('tll', '/style/1/img/tll.gif');
// 		$this->_smarty->assign('tlr', '/style/1/img/tlr.gif');
		$this->_templateParams['page'] = $this->page;
	}
	
	private function error($txt) {
		echo $txt;
	}
}