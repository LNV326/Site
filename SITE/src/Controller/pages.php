<?php
use Entity\EntitySPages;
use Entity\EntitySModules;
use Doctrine\Common\ClassLoader;

use Controller;
class Pages {
	private static $map = [ 
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
	];
	private $conf;
	private $DB;
	private $nfs;
	// private $em;
	private $page;
	public function __construct($conf, $nfs, $em, $DB) {
		$this->conf = $conf;
		// $this->em = $em;
		$this->nfs = $nfs;
		$this->DB = $DB;
	}
	public function set_page() {
		// Страница
		$this->page = null;
		if ($this->nfs->input [page] != '') {
			$this->DB->query( "SELECT * FROM s_pages WHERE name='" . addslashes( $this->nfs->input [page] ) . "';" );
			if (! $this->page = $this->DB->fetch_row()) {
				$this->DB->query( "SELECT * FROM s_modules WHERE name='" . addslashes( $this->nfs->input [page] ) . "';" );
				if (! $this->page = $this->DB->fetch_row()) {
					$default = 1;
				}
			}
		} else {
			$default = 1;
		}
		// Если выводим страницу по умолчанию
		if ($default == '1') {
			if ($this->conf [mainpage_type] == 0) {
				$this->DB->query( "SELECT * FROM s_modules WHERE name='" . $this->conf [mainpage_name] . "';" );
				if (! $this->page = $this->DB->fetch_row()) {
					$this->nfs->fatal_error( 'Ошибка вывода страницы!' );
				}
			} else {
				$this->DB->query( "SELECT * FROM s_pages WHERE name='" . $this->conf [mainpage_name] . "';" );
				if (! $this->page = $this->DB->fetch_row()) {
					$this->nfs->fatal_error( 'Ошибка вывода страницы!' );
				}
			}
		}
		return $this->page;
	}
	public function include_page() {
		global $em, $conf, $smarty, $ibforums, $INFO, $std, $style_id, $lang, $SDK, $sape_context, $DB, $nfs, $sdk_info, $admin;
		
		if ($conf ['adver_site_top_on'] == 1) {
			// if ($page[name]<>"news") {
			/*
			 * ?>
			 * <script type="text/javascript"><!--
			 * google_ad_client = "ca-pub-8369190706828575";
			 * google_ad_slot = "7094441647";
			 * google_ad_width = 468;
			 * google_ad_height = 60;
			 * //-->
			 * </script>
			 * <script type="text/javascript"
			 * src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			 * </script>
			 * <?
			 */
			// echo "<p style=\"margin:5px 5px 5px -364px; text-align:center; height:90px; width:728px; left:50%; position:relative;\">";
			echo "<p style=\"margin:5px 5px 5px -234px; text-align:center; height:60px; width:468px; left:50%; position:relative;\">";
			echo $conf ['adver_site_top_html'];
			echo "</p>";
			// } else {
			/*
			 * ?>
			 * <div style="width:100%;margin:5px 0 5px 0;"><object>
			 * <embed src="/files/roxen.swf" width="100%" height="55" style="border:0px;height:55px;"></embed>
			 * </object></div>
			 * <?
			 */
			// }
		}
		// Вывод
		if (isset( $this->page [module_path] )) {
			if ($this->page [name] != "news") {
				echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
				echo "<td><img src=\"/style/" . $style_id . "/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
				echo "<td width=\"98%\">&nbsp;<index><b>" . $this->page [title] . "</b></index></td>\n";
				echo "<td><img src=\"/style/" . $style_id . "/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
				echo "</tr></table>\n";
			}
			if (isset( self::$map [$this->page [name]] )) {
				$controllerLoader = new ClassLoader( 'Controller', '/home/sa/sites/nfsko.dev/src' );
				$controllerLoader->register();
				$controllerName = self::$map [$this->page [name]];
				$m = new $controllerName( $em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin );
				$m->index();
			} else
				include $this->page [module_path];
		} else {
			$ed_link = '';
			if ($SDK->is_supermod() or $SDK->is_admin()) {
				$ed_link = "[<a href=\"admin.php?adsess=&set=articles_edit&type=" . $this->page ['editor'] . "&id=" . $this->page ['id'] . "\">Редактировать</a>]";
			}
			echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
			echo "<td><img src=\"/style/" . $style_id . "/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
			echo "<td width=\"98%\">&nbsp;<index><b>" . $this->page [title] . "</b></index> " . $ed_link . "</td>\n";
			echo "<td><img src=\"/style/" . $style_id . "/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
			echo "</tr></table>\n";
			$this->page [counts] = $this->page [counts] + 1;
			
			// Убираем лишнее
			$this->page [html_page] = trim( stripslashes( $this->page [html_page] ) ) . "\n";
			// Ставим всем внешним ссылкам <noindex> и rel="nofollow"
			if (! in_array( $this->page ['id'], array (
					50,
					56,
					57,
					114,
					156 
			) )) {
				$this->page [html_page] = preg_replace( '#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU', '<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $this->page [html_page] );
			}
			// Выводим страницу
			if ($sape_show) {
				echo $sape_context->replace_in_text_segment( $this->page [html_page] );
			} else {
				echo $this->page ["html_page"];
			}
			
			if ($this->page ['root_page'] != '') {
				$DB->query( "SELECT title FROM s_pages WHERE name='" . $this->page ['root_page'] . "';" );
				$root_page = $DB->fetch_row();
				if ($root_page ['title'] != '') {
					echo "<div style=\"font-size:8pt;margin-left:0.2cm;margin-top:3pt\"><a href=\"http://" . $conf ['site_url'] . "\" title=\"" . $conf ['banner_desc'] . "\">" . $conf ['site_name'] . "</a> -=- <a href='index.php?page=" . $this->page ['root_page'] . "'>" . $root_page ['title'] . "</a> -=- " . $this->page ['title'] . "</div>\n";
				}
			}
			echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:96%;font-size:7pt;margin-top:5pt;margin-bottom:5pt\">\n";
			if ($this->page ['created_by'] > 0) {
				$DB->query( "SELECT name FROM `forum_members` WHERE id=" . $this->page ['created_by'] . ";" );
				$userinfo = $DB->fetch_row();
				if (! empty( $userinfo ['name'] )) {
					$text = "<a href='/forum/index.php?showuser=" . $this->page [created_by] . "'><b>" . $userinfo ['name'] . "</b></a>, " . date_smart( $this->page ['created'], 1 );
				} else {
					$text = "Не указано";
				}
			} else {
				$text = "Нет данных";
			}
			echo "<tr><td width=\"35%\">Страница создана: " . $text . "</td></tr>\n";
			echo "<tr><td width=\"35%\">Обращений к странице: <b>" . $this->page [counts] . "</b></td>\n";
			if ($this->page [page_info] != '') {
				echo "<td width=\"65%\" align=\"right\">" . $this->page [page_info] . "</td>\n";
			}
			echo "</tr></table>\n";
			
			include DIR . '/sources/3rdparty/comments.php';
			$comments = new comments( 'pages', $this->page ['id'] );
			echo $comments->Show();
			
			$DB->query( "UPDATE s_pages SET counts='" . intval( $this->page [counts] ) . "' WHERE name='" . $this->page ['name'] . "';" );
		}
		// Вывод объявлений
		if ($conf ['hotnews_on'] == 1) {
			?><div align="center">
	<noindex>
	<table class="tl" cellspacing="0" cellpadding="0"
		style="width: 96%; margin-top: 15pt">
		<tr>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tll.gif" alt=""
				border="0" /></td>
			<td width="100%"><b><? echo $conf[hotnews_title] ?></b></td>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tlr.gif" alt=""
				border="0" /></td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0"
		style="width: 96%; margin-top: 3pt">
		<tr>
			<td><div class="news"><? echo $conf[hotnews_text] ?></div></td>
		</tr>
	</table>
	<table class="newst" cellspacing="0" cellpadding="0"
		style="width: 96%; margin-top: 3pt">
		<tr>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tll.gif" alt=""
				border="0" /></td>
			<td width="80%"><? echo $conf[hotnews_autor] ?></td>
			<td width="20%" align="right"><a
				href="/forum/index.php?showtopic=<? echo $conf[hotnews_topic] ?>"
				target="_blank"><? echo $lang[comments]; ?></a></td>
			<td><img src="<? echo "/style/".$style_id."/img" ;?>/tlr.gif" alt=""
				border="0" /></td>
		</tr>
	</table>
	</noindex>
</div><?
		}
	}
}

/*
 *
 * //Класс страниц
 * class Pages {
 *
 * protected $page = array();
 * protected $type = '';
 * protected $default = 0;
 *
 * function Pages() {
 * global $conf,$DB,$nfs;
 * //Страница
 * if ($nfs->input[page]<>'') {
 * $DB->query("SELECT * FROM s_pages WHERE name='".addslashes($nfs->input[page])."';");
 * if (!$this->page = $DB->fetch_row() ) {
 * $DB->query("SELECT * FROM s_modules WHERE name='".addslashes($nfs->input[page])."';");
 * if (!$this-> = $DB->fetch_row() ) {
 * $this->default=1;
 * } else {
 * $this->type='module';
 * }
 * } else {
 * $this->type='page';
 * }
 * } else {
 * $this->default=1;
 * }
 * //Если выводим страницу по умолчанию
 * if ($this->default=='1') {
 * if ($conf[mainpage_type]==0) {
 * $DB->query("SELECT * FROM s_modules WHERE name='".$conf[mainpage_name]."';");
 * if ( !$this->page = $DB->fetch_row() ){
 * $nfs->fatal_error('Ошибка вывода страницы!');
 * } else {
 * $this->type='module';
 * }
 * } else {
 * $DB->query("SELECT * FROM s_pages WHERE name='".$conf[mainpage_name]."';");
 * if ( !$this->page = $DB->fetch_row() ){
 * $nfs->fatal_error('Ошибка вывода страницы!');
 * } else {
 * $this->type='page';
 * }
 * }
 * }
 * }
 *
 * function include_page() {
 * global $conf,$style_id,$lang,$page,$DB,$type,$nfs,$php_poll,$SDK,$sdk_info,$std,$admins,$Debug,$sape_context,$smarty;
 * if ($conf['adver_site_top_on']==1){
 * echo "<p style=\"margin:0.1cm; text-align: center;\">";
 * echo $conf['adver_site_top_html'];
 * echo "</p>";
 * }
 * //Вывод
 * if ($type=='module') {
 * if ($page[name]<>"news") {
 * echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
 * echo "<td><img src=\"/style/".$style_id."/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
 * echo "<td width=\"98%\">&nbsp;<index><b>".$page[title]."</b></index></td>\n";
 * echo "<td><img src=\"/style/".$style_id."/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
 * echo "</tr></table>\n";
 * }
 * include $page[module_path];
 * if ($page[name]=="news") {module_go();}
 * } else {
 * $ed_link = '';
 * if ($SDK->is_supermod() OR $SDK->is_admin()) {
 * $ed_link = "[<a href=\"admin.php?adsess=&set=articles_edit&type=".$page['editor']."&id=".$page['id']."\">Редактировать</a>]";
 * }
 * echo "<table align=\"center\" class=\"newst\" cellspacing=\"0\" cellpadding=\"0\"><tr>\n";
 * echo "<td><img src=\"/style/".$style_id."/img/tll.gif\" alt=\"\" border=\"0\"/></td>\n";
 * echo "<td width=\"98%\">&nbsp;<index><b>".$page[title]."</b></index> ".$ed_link."</td>\n";
 * echo "<td><img src=\"/style/".$style_id."/img/tlr.gif\" alt=\"\" border=\"0\"/></td>\n";
 * echo "</tr></table>\n";
 * $page[counts]=$page[counts]+1;
 *
 * //Убираем лишнее
 * $page[html_page]=trim(stripslashes($page[html_page]))."\n";
 * //Ставим всем внешним ссылкам <noindex> и rel="nofollow"
 * $page[html_page]=preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU',
 * '<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $page[html_page]);
 * //Sape Context
 * if ($sape_show) $page[html_page]=$sape_context->replace_in_text_segment($page[html_page]);
 * //Выводим страницу
 * echo $page[html_page];
 *
 * if ($page['root_page']<>'') {
 * $DB->query("SELECT title FROM s_pages WHERE name='".$page['root_page']."';");
 * $root_page = $DB->fetch_row();
 * if ($root_page['title']<>'') {
 * echo "<div style=\"font-size:8pt;margin-left:0.2cm;margin-top:3pt\"><a href=\"http://".$conf['site_url']."\" title=\"".$conf['banner_desc']."\">".$conf['site_name']."</a> -=- <a href='index.php?page=".$page['root_page']."'>".$root_page['title']."</a> -=- ".$page['title']."</div>\n";
 * }
 * }
 * echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:96%;font-size:7pt;margin-top:5pt;margin-bottom:5pt\">\n";
 * if ($page['created_by']>0) {
 * $DB->query("SELECT name FROM `forum_members` WHERE id=".$page['created_by'].";");
 * $userinfo = $DB->fetch_row();
 * if (!empty($userinfo['name'])) {
 * $text = "<a href='/forum/index.php?showuser=".$page[created_by]."'><b>".$userinfo['name']."</b></a>, ".date_smart($page['created'],1);
 * } else {
 * $text = "Не указано";
 * }
 * } else {
 * $text = "Нет данных";
 * }
 * echo "<tr><td width=\"35%\">Страница создана: ".$text."</td></tr>\n";
 * echo "<tr><td width=\"35%\">Обращений к странице: <b>".$page[counts]."</b></td>\n";
 * if ($page[page_info]<>'') {
 * echo "<td width=\"65%\" align=\"right\">".$page[page_info]."</td>\n";
 * }
 * echo "</tr></table>\n";
 *
 * include DIR.'/sources/3rdparty/comments.php';
 * $comments=new comments('pages',$page['id']);
 * echo $comments->Show();
 *
 * $DB->query("UPDATE s_pages SET counts='".intval($page[counts])."' WHERE name='".$page['name']."';");
 * }
 * //Вывод объявлений
 * if ( $conf['hotnews_on'] == 1){
 * ?><div align="center"><noindex>
 * <table class="tl" cellspacing="0" cellpadding="0" style="width:96%;margin-top:15pt"><tr>
 * <td><img src="<? echo "/style/".$style_id."/img" ;?>/tll.gif" alt="" border="0"/></td>
 * <td width="100%"><b><? echo $conf[hotnews_title] ?></b></td>
 * <td><img src="<? echo "/style/".$style_id."/img" ;?>/tlr.gif" alt="" border="0"/></td>
 * </tr></table>
 * <table cellspacing="0" cellpadding="0" style="width:96%;margin-top:3pt">
 * <tr><td><div class="news"><? echo $conf[hotnews_text] ?></div></td></tr>
 * </table>
 * <table class="newst" cellspacing="0" cellpadding="0" style="width:96%;margin-top:3pt"><tr>
 * <td><img src="<? echo "/style/".$style_id."/img" ;?>/tll.gif" alt="" border="0"/></td>
 * <td width="80%"><? echo $conf[hotnews_autor] ?></td>
 * <td width="20%" align="right"><a href="/forum/index.php?showtopic=<? echo $conf[hotnews_topic] ?>" target="_blank"><? echo $lang[comments]; ?></a></td>
 * <td><img src="<? echo "/style/".$style_id."/img" ;?>/tlr.gif" alt="" border="0"/></td>
 * </tr></table>
 * </noindex></div><?
 * }
 *
 * if ($sdk_info[id]==1 OR $sdk_info[id]==281) {
 * echo "<tr><td>От начала генерации:<br>".$Debug->endTimer()."</td></tr>";
 * }
 * }
 * }
 */
?>