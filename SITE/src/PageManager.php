<?php

use Controller;
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
		
	private static $_map = array(
			'about' => 'Controller\AboutControllerDB',
			'add_news' => 'Controller\AddNewsControllerDB',
			'adver' => 'Controller\AdverControllerDB',
			'articles' => 'Controller\ArticlesControllerDB',
			'chat' => 'Controller\ChatControllerDB',
			'confirm_load' => 'Controller\ConfirmLoadControllerDB',
			'contact' => 'Controller\ContactControllerDB',
			'faq' => 'Controller\FAQControllerDB',
			'files' => 'Controller\FilesControllerDB',
			'gadgets' => 'Controller\GadgetsController',
			'gallery' => 'Controller\GalleryController',
			'info' => 'Controller\InfoControllerDB',
			'links' => 'Controller\LinksControllerDB',
			'login' => 'Controller\LoginControllerDB',
			'news' => 'Controller\NewsControllerDB',
			'search' => 'Controller\SearchControllerDB',
			'sms_money' => 'Controller\SMSMoneyControllerDB',
			'stat' => 'Controller\StatisticControllerDB',
			'uploads' => 'Controller\UploadsController',
			'userbars' => 'Controller\UserbarsControllerDB'
	);
	private $_DB;
	private $_conf;
	private $_nfs;
	private $_page;
	
	public function __construct($DB, $conf, $nfs) {
		$this->_DB = $DB;
		$this->_conf = $conf;
		$this->_nfs = $nfs;
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
		if ($this->_conf['adver_site_top_on']==1){
			//if ($page[name]<>"news") {
			/*?>
			<script type="text/javascript"><!--
			google_ad_client = "ca-pub-8369190706828575";
			google_ad_slot = "7094441647";
			google_ad_width = 468;
			google_ad_height = 60;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
			<?*/
			//echo "<p style=\"margin:5px 5px 5px -364px; text-align:center; height:90px; width:728px; left:50%; position:relative;\">";
			echo "<p style=\"margin:5px 5px 5px -234px; text-align:center; height:60px; width:468px; left:50%; position:relative;\">";
			echo $this->_conf['adver_site_top_html'];
			echo "</p>";
			//} else {
			/*?>
			<div style="width:100%;margin:5px 0 5px 0;"><object>
			<embed src="/files/roxen.swf" width="100%" height="55" style="border:0px;height:55px;"></embed>
			</object></div>
			<?*/
			//}
		}
		//Вывод
		if (isset($this->_page['module_path'])) {
			if ($this->_page[name]<>"news") {
				echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
				echo "<td><img src=\"/style/".$style_id."/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
				echo "<td width=\"98%\">&nbsp;<index><b>".$this->_page[title]."</b></index></td>\n";
				echo "<td><img src=\"/style/".$style_id."/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
				echo "</tr></table>\n";
			}
			if (isset( self::$_map [$this->_page [name]] )) {
				$controllerName = self::$_map [$this->_page [name]];
				$m = new $controllerName( $em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin );
				$m->index();
			} else
				include $this->_page [module_path];
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