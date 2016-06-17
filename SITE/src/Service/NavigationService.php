<?php

namespace Service;

use Utils\SingletonInterface;

final class NavigationService implements SingletonInterface {
	
	/* ====================== */
	/* ======= STATIC ======= */
	
	/**
	 * An instance of class
	 * @var SingletonInterface
	 */
	private static $_instance;
	private static $_conf;
	private static $_DB;
	
	/* (non-PHPdoc)
	 * @see \Utils\SingletonInterface::getInstance()
	 */
	public static function getInstance() {
		if ( is_null(self::$_instance) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	/* (non-PHPdoc)
	 * @see \Utils\SingletonInterface::hasInstance()
	 */
	public static function hasInstance() {
		return ( is_null(self::$_instance) ) ? false : true;
	}
	
	/* ======================= */
	/* ======= DYNAMIC ======= */
	
	/**
	 * Protected constructor for nobody it this but this class itself (Singleton)
	 */
	protected function __construct() {
		global $conf, $DB;
		self::$_conf = $conf;
		self::$_DB = $DB;
	}
	
	/**
	 * Protected cloner for nobody call it but this class itself (Singleton)
	 */
	protected function __clone() {}
	
	public function getMainMenu() {
		//Подсчёт кол-ва пользователей в чате!
		self::$_DB->query("SELECT count(uid) as act_count FROM ibf_chatonline LIMIT 1");
		$row = self::$_DB->fetch_row();
		$count=$row['act_count'];
		// Remove old sessions from chat
		if (!empty($count)) self::$_DB->query("DELETE FROM ibf_chatonline WHERE (".time()."-time)>600"); //10 мин
		// TODO Dont need to remove ols sessions, need to count only active sessions (minus one DB query)
	
		//Элементы меню
		$main_menu=array();
		$main_menu[] = array("ru" => "Новости сайта", "en" => "Site News", "url" => "/", "image" => "rss.png", "image_title" => "RSS News", "image_link" => "http://www.".self::$_conf[site_url]."/modules/rss.php", 'type' => 'link');
		$main_menu[] = array("ru" => "Форум сайта", "en" => "Forum", "url" => "/forum/", 'type' => 'link');//, "image" => "wap.png", "image_link" => "http://wap.nfsko.ru", "image_title" => "Wap Forum", "noindex" => true);
		$main_menu[] = array("ru" => "Чат сайта (".$count.")", "en" => "Chat (".$count.")", "url" => "/chat/", "blank" => "1", 'type' => 'link');
		$main_menu[] = array("ru" => "Файловый архив", "en" => "Files", "url" => "http://files.nfsko.ru", "noindex" => true, 'type' => 'link');
		$main_menu[] = array("ru" => "Галерея сайта", "en" =>"Gallery", "url"=> "http://images.nfsko.ru", "noindex" => true, 'type' => 'link');
		$main_menu[] = array("ru" => "Вопросы и ответы", "en" => "FAQ", "url" => "/index.php?page=faq", 'type' => 'link');
		$main_menu[] = array("ru" => "Поиск по сайту", "en"	=> "Search", "url"	=> "/index.php?page=search", 'type' => 'link');
		//$main_menu[] = array("ru" => "Статистика сайта", "en" => "Statistics", "url" => "/index.php?page=stat", 'type' => 'link');
		$main_menu[] = array("ru" => "Ссылки на сайты", "en" => "Links", "url"	=> "/index.php?page=links", 'type' => 'link');
		//$main_menu[] = array("ru" => "Наши Userbar's", "en" => "Userbar's", "url" => "/index.php?page=userbars", 'type' => 'link');
		//$main_menu[] = array("ru" => "Наши кнопки", "en" => "Our banners", "url" => "/index.php?page=info", 'type' => 'link');
		//$main_menu[] = array("ru" => "Реклама на сайте", "en" => "Advertising", "url" => "/index.php?page=adver", 'type' => 'link');
		$main_menu[] = array("ru" => "Добавить файлы", "en"	=> "Upload files", "url" => "http://files.nfsko.ru/index.php?page=upload", "noindex" => true, "isBold" => true, 'type' => 'link');
		$main_menu[] = array("ru" => "Добавить новость", "en" => "Add news", "url" => "/index.php?page=add_news", "isBold" => true, 'type' => 'link');
		return $main_menu;
	}
	
	public function getMenuCategories() {
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