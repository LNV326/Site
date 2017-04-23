<?php

use Controller;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
/**
 * 
 * Refactoring from old site engine version (year 2003).
 * 
 * @author Nikolay Lukyanov
 *
 * @version 1.0 
 *
 */
class PageManager {
		
// 	private static $_map = array(
// 			'about' => 'Controller\AboutControllerDB',
// 			'add_news' => 'Controller\AddNewsControllerDB',
// 			'adver' => 'Controller\AdverControllerDB',
// 			'articles' => 'Controller\ArticlesControllerDB',
// 			'chat' => 'Controller\ChatControllerDB',
// 			'confirm_load' => 'Controller\ConfirmLoadControllerDB',
// 			'contact' => 'Controller\ContactControllerDB',
// 			'faq' => 'Controller\FAQControllerDB',
// 			'files' => 'Controller\FilesControllerDB',
// 			'gadgets' => 'Controller\GadgetsController',
// 			'gallery' => 'Controller\GalleryController',
// 			'info' => 'Controller\InfoControllerDB',
// 			'links' => 'Controller\LinksControllerDB',
// 			'login' => 'Controller\LoginControllerDB',
// 			'news' => 'Controller\NewsControllerDB',
// 			'search' => 'Controller\SearchControllerDB',
// 			'sms_money' => 'Controller\SMSMoneyControllerDB',
// 			'stat' => 'Controller\StatisticControllerDB',
// 			'uploads' => 'Controller\UploadsController',
// 			'userbars' => 'Controller\UserbarsControllerDB'
// 	);
	
	private $_DB;
	private $_conf;
	private $_nfs;
	private $_page;
	private $_matcher;
	
	public function __construct($DB, $conf, $nfs) {
		$this->_DB = $DB;
		$this->_conf = $conf;
		$this->_nfs = $nfs;
		
		$_map = array(
				'default' 			=> new Route('/', 			array('_controller' => 'Controller\NewsControllerDB')),
				/* === News === */
				'show_news' 		=> new Route('news={pageNum}', 		array('_controller' => 'Controller\NewsControllerDB:showAction', 'pageNum' => 1), array('pageNum' => '\d+')),
				'add_news'			=> new Route('page=add_news', 	array('_controller' => 'Controller\AddNewsControllerDB')),
				/* === FAQ === */
				'show_faq_main'		=> new Route('page=faq', array('_controller' => 'Controller\FAQControllerDB')),
				'show_faq_category'	=> new Route('page=faq&cat={categoryId}', array('_controller' => 'Controller\FAQControllerDB'), array('categoryId' => '\d+')),
				/* === === */
				'about'				=> new Route('page=about', array('_controller' => 'Controller\AboutControllerDB')),
				'adver'				=> new Route('page=adver', array('_controller' => 'Controller\AdverControllerDB')),
				'articles'			=> new Route('page=articles', array('_controller' => 'Controller\ArticlesControllerDB')),
				'chat'				=> new Route('page=chat', array('_controller' => 'Controller\ChatControllerDB')),
				'confirm_load'		=> new Route('page=confirm_load', array('_controller' => 'Controller\ConfirmLoadControllerDB')),
				'contact'			=> new Route('page=contact', array('_controller' => 'Controller\ContactControllerDB')),
				'files'				=> new Route('page=files', array('_controller' => 'Controller\FilesControllerDB')),
				'gadgets'			=> new Route('page=gadgets', array('_controller' => 'Controller\GadgetsController')),
				'gallery'			=> new Route('page=gallery', array('_controller' => 'Controller\GalleryController')),
				'info'				=> new Route('page=info', array('_controller' => 'Controller\InfoControllerDB')),
				'links'				=> new Route('page=links', array('_controller' => 'Controller\LinksControllerDB')),
				'login'				=> new Route('page=login', array('_controller' => 'Controller\LoginControllerDB')),
				'search'			=> new Route('page=search', array('_controller' => 'Controller\SearchControllerDB')),
				'sms_money'			=> new Route('page=sms_money', array('_controller' => 'Controller\SMSMoneyControllerDB')),
				'stat'				=> new Route('page=stat', array('_controller' => 'Controller\StatisticControllerDB')),
				'uploads'			=> new Route('page=uploads', array('_controller' => 'Controller\UploadsController')),
				'userbars'			=> new Route('page=userbars', array('_controller' => 'Controller\UserbarsControllerDB'))
		);
		
		$routes = new RouteCollection();
		foreach ($_map as $key => $value)
			$routes->add($key, $value);
		$context = new RequestContext('/');
		$this->_matcher = new UrlMatcher($routes, $context);		
	}
	
	public function set_page() {
		//Страница
		if ($this->_nfs->input[page]<>'') {
			$this->_DB->query("SELECT * FROM s_pages WHERE name='".addslashes($this->_nfs->input[page])."';");
			if (!$this->_page = $this->_DB->fetch_row() ) {
				$this->_DB->query("SELECT * FROM s_modules WHERE name='".addslashes($this->_nfs->input[page])."';");
				if (!$this->_page = $this->_DB->fetch_row() ) {
					$default=1;
				}
			}
		} else {
			$default=1;
		}
		//Если выводим страницу по умолчанию
		if ($default=='1') {
			if ($this->_conf[mainpage_type]==0) {
				$this->_DB->query("SELECT * FROM s_modules WHERE name='".$this->_conf[mainpage_name]."';");
				if ( !$this->_page = $this->_DB->fetch_row() ){
					$this->_nfs->fatal_error('Ошибка вывода страницы!');
				}
			} else {
				$this->_DB->query("SELECT * FROM s_pages WHERE name='".$this->_conf[mainpage_name]."';");
				if ( !$this->_page = $this->_DB->fetch_row() ){
					$this->_nfs->fatal_error('Ошибка вывода страницы!');
				}
			}
		}
		return $this->_page;
	}
	
	public function include_page() {
// 		global $style_id,$lang,$page,$DB,$type,$nfs,$php_poll,$SDK,$sdk_info,$std,$admins,$Debug,$sape_context,$smarty;
// 		global $em, $smarty, $ibforums, $INFO, $std, $style_id, $lang, $SDK, $sape_context, $DB, $nfs, $sdk_info, $admin;
		global $em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin, $sape_context, $sape_show;
		
		//Вывод
		if (isset($this->_page['module_path'])) {
			if ($this->_page[name]<>"news") {
				echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
				echo "<td><img src=\"/style/".$style_id."/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
				echo "<td width=\"98%\">&nbsp;<index><b>".$this->_page[title]."</b></index></td>\n";
				echo "<td><img src=\"/style/".$style_id."/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
				echo "</tr></table>\n";
			}
			// Looking for requested URL
			$qs = $_SERVER['QUERY_STRING'];
			try {
				$parameters = $this->_matcher->match('/'.$qs);
				if ( $controllerInto = preg_split('/:/', $parameters['_controller']) ) {
					$controllerName = $controllerInto[0];
// 					$methodName = $controllerInto[1];
					$methodName = 'index'; // TODO At this time always index method (abstract)
				} else {
					$controllerName = $parameters['_controller'];
					$methodName = 'index';
				}
				unset($parameters['_controller'], $parameters['_route']);
				$m = new $controllerName( $em, $DB, $conf, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin );
				call_user_func_array(array($m, $methodName), $parameters);
			} catch (ResourceNotFoundException $e) {
				// TODO Need to response 404 error
				echo $e->getMessage();
				include $this->_page [module_path];
			} catch (Exception $e) {
				// TODO Need to response 404 error
				echo $e->getMessage();
			}
// 			if (isset( self::$_map [$this->_page [name]] )) {
// 				$controllerName = self::$_map [$this->_page [name]];
// 				$m = new $controllerName( $em, $DB, $conf, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin );
// 				$m->index();
// 			} else
// 				include $this->_page [module_path];
		} else {
			$ed_link = '';
			if ($SDK->is_supermod() OR $SDK->is_admin()) {
				$ed_link = "[<a href=\"admin.php?adsess=&set=articles_edit&type=".$this->_page['editor']."&id=".$this->_page['id']."\">Редактировать</a>]";
			}
			echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
			echo "<td><img src=\"/style/".$style_id."/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
			echo "<td width=\"98%\">&nbsp;<index><b>".$this->_page[title]."</b></index> ".$ed_link."</td>\n";
			echo "<td><img src=\"/style/".$style_id."/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
			echo "</tr></table>\n";
			$this->_page[counts]=$this->_page[counts]+1;
	
			//Убираем лишнее
			$this->_page[html_page]=trim(stripslashes($this->_page[html_page]))."\n";
			//Ставим всем внешним ссылкам <noindex> и rel="nofollow"
			if (!in_array($this->_page['id'],array(50,56,57,114,156))) {
				$this->_page[html_page]=preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU',
						'<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $this->_page[html_page]);
			}
			//Выводим страницу
			if ($sape_show) {
				echo $sape_context->replace_in_text_segment($this->_page[html_page]);
			} else {
				echo $this->_page["html_page"];
			}
	
			if ($this->_page['root_page']<>'') {
				$this->_DB->query("SELECT title FROM s_pages WHERE name='".$this->_page['root_page']."';");
				$root_page = $this->_DB->fetch_row();
				if ($root_page['title']<>'') {
					echo "<div style=\"font-size:8pt;margin-left:0.2cm;margin-top:3pt\"><a href=\"http://".$this->_conf['site_url']."\" title=\"".$this->_conf['banner_desc']."\">".$this->_conf['site_name']."</a> -=- <a href='index.php?page=".$this->_page['root_page']."'>".$root_page['title']."</a> -=- ".$this->_page['title']."</div>\n";
				}
			}
			echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:96%;font-size:7pt;margin-top:5pt;margin-bottom:5pt\">\n";
			if ($this->_page['created_by']>0) {
				$this->_DB->query("SELECT name FROM `forum_members` WHERE id=".$this->_page['created_by'].";");
				$userinfo = $this->_DB->fetch_row();
				if (!empty($userinfo['name'])) {
					$text = "<a href='/forum/index.php?showuser=".$this->_page[created_by]."'><b>".$userinfo['name']."</b></a>, ".date_smart($this->_page['created'],1);
				} else {
					$text = "Не указано";
				}
			} else {
				$text = "Нет данных";
			}
			echo "<tr><td width=\"35%\">Страница создана: ".$text."</td></tr>\n";
			echo "<tr><td width=\"35%\">Обращений к странице: <b>".$this->_page[counts]."</b></td>\n";
			if ($this->_page[page_info]<>'') {
				echo "<td width=\"65%\" align=\"right\">".$this->_page[page_info]."</td>\n";
			}
			echo "</tr></table>\n";
	
			include DIR.'/sources/3rdparty/comments.php';
			$comments=new comments('pages',$this->_page['id']);
			echo $comments->Show();
	
			$this->_DB->query("UPDATE s_pages SET counts='".intval($this->_page[counts])."' WHERE name='".$this->_page['name']."';");
		}
		//Вывод объявлений
		if ( $this->_conf['hotnews_on'] == 1){
			?><div align="center"><noindex>
			<table class="tl" cellspacing="0" cellpadding="0" style="width:96%;margin-top:15pt"><tr>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tll.gif" alt="" border="0"/></td>
			<td width="100%"><b><? echo $this->_conf[hotnews_title] ?></b></td>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tlr.gif" alt="" border="0"/></td>
			</tr></table>
			<table cellspacing="0" cellpadding="0" style="width:96%;margin-top:3pt">
			<tr><td><div class="news"><? echo $this->_conf[hotnews_text] ?></div></td></tr>
			</table>
			<table class="newst" cellspacing="0" cellpadding="0" style="width:96%;margin-top:3pt"><tr>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tll.gif" alt="" border="0"/></td>
			<td width="80%"><? echo $this->_conf[hotnews_autor] ?></td>
			<td width="20%" align="right"><a href="/forum/index.php?showtopic=<? echo $this->_conf[hotnews_topic] ?>" target="_blank"><? echo $lang[comments]; ?></a></td>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tlr.gif" alt="" border="0"/></td>
			</tr></table>
			</noindex></div><?
		}
	}
}