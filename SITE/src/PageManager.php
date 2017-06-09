<?php

use Controller;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Routing\RouteLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
/**
 * 
 * Refactoring from old site engine version (year 2003).
 * 
 * @author Nikolay Lukyanov
 *
 * @version 1.2
 *
 */
class PageManager {
			
	private $_DB;
	private $_conf;
	private $_nfs;
	private $_page;
	private $_matcher;
	private $_routeParameters;
	
	public function __construct($DB, $conf, $nfs) {
		$this->_DB = $DB;
		$this->_conf = $conf;
		$this->_nfs = $nfs;
		
		$rl = new RouteLoader();
		$routes = $rl->load(null);
		$this->_matcher = new UrlMatcher($routes, new RequestContext('/'));		
	}
	
	public function set_page() {
		// Looking for requested URL
		$qs = $_SERVER['QUERY_STRING'];
		try {
			$this->_routeParameters = $this->_matcher->match('/'.$qs);
			// TODO This is bad lifehack... create an ArticlesControllerDB instance
			if (strpos($this->_routeParameters['_controller'], 'ArticlesControllerDB') === false) {
				if (isset($this->_nfs->input['page']))
					$this->_DB->query("SELECT * FROM s_modules WHERE name='".addslashes($this->_nfs->input['page'])."';");
				else
					$this->_DB->query("SELECT * FROM s_modules WHERE name='".addslashes($this->_conf['mainpage_name'])."';");
			} else 
				$this->_DB->query("SELECT * FROM s_pages WHERE name='".addslashes($this->_nfs->input['page'])."';");
			if (!$this->_page = $this->_DB->fetch_row() ) {
				// TODO
				// $this->_conf[mainpage_type] is depricated
				// $this->_conf[mainpage_name] is depricated				
				throw new ResourceNotFoundException('Page not found');
			}

		} catch (ResourceNotFoundException $e) {
			// TODO Need to response 404 error
			$_GET['error'] = 404;
// 			include $this->_page [module_path];
		} catch (Exception $e) {
			// TODO Need to response 404 error
			$_GET['error'] = 404;
			echo $e->getMessage();
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
			try {					
				$this->controllerDelegator();
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		} else {
			$c = new Controller\ArticlesControllerDB($em, $DB, $conf, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin);
			$c->showArticleAction($this->_routeParameters['articleName']);
			
// 			$ed_link = '';
// 			if ($SDK->is_supermod() OR $SDK->is_admin()) {
// 				$ed_link = "[<a href=\"admin.php?adsess=&set=articles_edit&type=".$this->_page['editor']."&id=".$this->_page['id']."\">Редактировать</a>]";
// 			}
// 			echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
// 			echo "<td><img src=\"/style/".$style_id."/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
// 			echo "<td width=\"98%\">&nbsp;<index><b>".$this->_page[title]."</b></index> ".$ed_link."</td>\n";
// 			echo "<td><img src=\"/style/".$style_id."/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
// 			echo "</tr></table>\n";
// 			$this->_page[counts]=$this->_page[counts]+1;
	
// 			//Убираем лишнее
// 			....	
			include DIR.'/sources/3rdparty/comments.php';
			$comments=new comments('pages',$this->_page['id']);
			echo $comments->Show();
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
	
	protected function controllerDelegator() {
		global $em, $DB, $conf, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin;
		
		list($controllerName, $methodName) = explode(':', $this->_routeParameters['_controller']);
		$methodName = is_null($methodName) ? 'index' : $methodName;
		try {
			$m = new $controllerName( $em, $DB, $conf, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin );			
			unset($this->_routeParameters['_controller'], $this->_routeParameters['_route']); // Don't remove this!
			call_user_func_array(array($m, $methodName), $this->_routeParameters);
		} catch (Exception $e) {
			echo $e->__toString();
		}
	}
}