<?php

/**
 * 
 * @author Nikolay Lukyanov
 *
 */
abstract class NavigationManager {
	
	private static $_conf;
	private static $_DB;
	
	public static function init($conf, $DB) {
		self::$_conf = $conf;
		self::$_DB = $DB;
	}
	
	public static function getMainMenu() {
		//Подсчёт кол-ва пользователей в чате!
		self::$_DB->query("SELECT count(uid) as act_count FROM ibf_chatonline LIMIT 1");
		$row = self::$_DB->fetch_row();
		$count=$row['act_count'];
		// Remove old sessions from chat
		if (!empty($count)) self::$_DB->query("DELETE FROM ibf_chatonline WHERE (".time()."-time)>600"); //10 мин
	 	// TODO Dont need to remove ols sessions, need to count only active sessions (minus one DB query)
		
		//Элементы меню
		$main_menu=array();
		$main_menu[] = array("ru" => "Новости сайта", "en" => "Site News", "link" => "/", "image" => "rss.png", "image_title" => "RSS News", "image_link" => "http://www.".self::$_conf[site_url]."/modules/rss.php");
		$main_menu[] = array("ru" => "Форум сайта", "en" => "Forum", "link" => "/forum/");//, "image" => "wap.png", "image_link" => "http://wap.nfsko.ru", "image_title" => "Wap Forum", "noindex" => true);
		$main_menu[] = array("ru" => "Чат сайта (".$count.")", "en" => "Chat (".$count.")", "link" => "/chat/", "blank" => "1");
		$main_menu[] = array("ru" => "Файловый архив", "en" => "Files", "link" => "http://files.nfsko.ru", "noindex" => true);
		$main_menu[] = array("ru" => "Галерея сайта", "en" =>"Gallery", "link"=> "http://images.nfsko.ru", "noindex" => true);
		$main_menu[] = array("ru" => "Вопросы и ответы", "en" => "FAQ", "link" => "/index.php?page=faq" );
		$main_menu[] = array("ru" => "Поиск по сайту", "en"	=> "Search", "link"	=> "/index.php?page=search" );
		//$main_menu[] = array("ru" => "Статистика сайта", "en" => "Statistics", "link" => "/index.php?page=stat" );
		$main_menu[] = array("ru" => "Ссылки на сайты", "en" => "Links", "link"	=> "/index.php?page=links" );
		//$main_menu[] = array("ru" => "Наши Userbar's", "en" => "Userbar's", "link" => "/index.php?page=userbars" );
		//$main_menu[] = array("ru" => "Наши кнопки", "en" => "Our banners", "link" => "/index.php?page=info" );
		//$main_menu[] = array("ru" => "Реклама на сайте", "en" => "Advertising", "link" => "/index.php?page=adver" );
		$main_menu[] = array("ru" => "<b>Добавить файлы</b>", "en"	=> "<b>Upload files</b>", "link" => "http://files.nfsko.ru/index.php?page=upload", "noindex" => true);
		$main_menu[] = array("ru" => "<b>Добавить новость</b>", "en" => "<b>Add news</b>", "link" => "/index.php?page=add_news" );
		return $main_menu;
	}
	
	public static function getMenuCategories() {
		global $nfs,$sdk_info;
		// TODO need to remove globals
		$i=0;
		$noshow=null;
	
		if (!empty($sdk_info[id]) AND ($sdk_info[id]<>'0')) {
			if (!empty($sdk_info[menu_viever])) {
				foreach( explode( ",", $sdk_info[menu_viever]) as $el ) {
					$noshow[$i]=$el;
					$i+=1;
				}
			}
		} else {
			if (!$_COOKIE['menu_viever']==null) {
				foreach( explode( ",", $_COOKIE['menu_viever']) as $el ) {
					$noshow[$i]=$el;
					$i+=1;
				}
			}
		}
	
		self::$_DB->query("SELECT c.id,c.name,i.info,i.type,i.url,i.new,i.open_new FROM s_menu_cat c LEFT JOIN s_menu_items i ON (c.id=i.cat_id) ORDER BY c.poz, i.poz ASC");
		$categories = array();
		while ($row = self::$_DB->fetch_row()) {
			// Get or init new category
			if (!array_key_exists($row['id'], $categories)) {
				$categories[$row['id']] = array(
						'id'	=> $row['id'],
						'name'	=> $row['name'],
						'items' => array(),
						'isOpen' => (in_array($row['id'], $noshow)) ? false : true
				);
			}
			$cat = & $categories[$row['id']];
			$cat['items'][] = array(
					'info'	=> $row['info'],
					'type'	=> $row['type'],
					'url'	=> $row['url'],
					'new'	=> $row['new'],
					'open_new' => $row['open_new']
			);
		}
// 		$return_html=str_replace("&","&amp;",$return_html);
		return $categories;
	}
}