<?php

namespace Utils;

/**
 * 
 * @author NLukyanov (LNV)
 *
 */
abstract class RedirectService {
	
	private static $links_no_redirect = "http://nfsko.ru/index.php?page=gallery&cat=4
http://nfsko.ru/index.php?page=gallery&subcat=83
http://nfsko.ru/index.php?page=files&cat=3
http://nfsko.ru/index.php?page=files&cat=3
http://nfsko.ru/index.php?page=files&subcat=57&p=9&sort=name
http://nfsko.ru/index.php?page=files&subcat=43&p=1&sort=name
http://nfsko.ru/index.php?page=gallery&view=160
http://nfsko.ru/index.php?page=gallery&view=75&p=2
http://nfsko.ru/index.php?page=gallery&view=7
http://nfsko.ru/index.php?page=gallery&user=21992
http://nfsko.ru/index.php?page=gallery&view=107
http://nfsko.ru/index.php?page=gallery&view=47
http://nfsko.ru/index.php?page=gallery&view=161
http://nfsko.ru/index.php?page=gallery&view=76&p=3
http://nfsko.ru/index.php?page=gallery&view=150
http://nfsko.ru/index.php?page=gallery&view=152
http://nfsko.ru/index.php?page=gallery&view=100
http://nfsko.ru/index.php?page=gallery&view=94
http://nfsko.ru/index.php?page=gallery&subcat=1
http://nfsko.ru/index.php?page=gallery&view=2
http://nfsko.ru/index.php?page=gallery&user=19678
http://nfsko.ru/index.php?page=gallery&view=33
http://nfsko.ru/index.php?page=gallery&user=20663
http://nfsko.ru/index.php?page=gallery&user=1
http://nfsko.ru/index.php?page=gallery&user=19515
http://nfsko.ru/index.php?page=gallery&view=32
http://nfsko.ru/index.php?page=gallery&view=3
http://nfsko.ru/index.php?page=gallery&user=6141
http://nfsko.ru/index.php?page=gallery&user=22772
http://nfsko.ru/index.php?page=gallery&view=46
http://nfsko.ru/index.php?page=gallery&view=52
http://nfsko.ru/index.php?page=gallery&view=74
http://nfsko.ru/index.php?page=gallery&user=26723
http://nfsko.ru/index.php?page=gallery&view=7
http://nfsko.ru/index.php?page=gallery&user=20648
http://nfsko.ru/index.php?page=gallery&view=148&p=9
http://nfsko.ru/index.php?page=gallery&view=62
http://nfsko.ru/index.php?page=files&subcat=54
http://nfsko.ru/index.php?page=files&subcat=53
http://nfsko.ru/index.php?page=gallery&view=37
http://nfsko.ru/index.php?page=files&subcat=54
http://nfsko.ru/index.php?page=gallery&view=136
http://nfsko.ru/index.php?page=gallery&user=19888
http://nfsko.ru/index.php?page=gallery&view=30
http://nfsko.ru/index.php?page=gallery
http://nfsko.ru/index.php?page=gallery&user=19888
http://nfsko.ru/index.php?page=gallery&view=29
http://nfsko.ru/index.php?page=gallery&view=166
http://nfsko.ru/index.php?page=gallery&view=29
http://nfsko.ru/index.php?page=gallery&user=18287
http://nfsko.ru/index.php?page=gallery&view=23
http://nfsko.ru/index.php?page=gallery&view=158&p=5
http://nfsko.ru/index.php?page=gallery&view=45
http://nfsko.ru/index.php?page=gallery&cat=1
http://nfsko.ru/index.php?page=gallery&user=21960
http://nfsko.ru/index.php?page=gallery&view=15
http://nfsko.ru/index.php?page=gallery&cat=0
http://nfsko.ru/index.php?page=gallery&cat=5
http://nfsko.ru/index.php?page=gallery&cat=11
http://nfsko.ru/index.php?page=gallery&user=22037";
	
	public static function check() {
		if ($_GET['page']=='gallery' || $_GET['page']=='files') {
			self::$links_no_redirect = explode("\n",$links_no_redirect);
			if (!in_array("http://nfsko.ru".$_SERVER['REQUEST_URI'],$links_no_redirect)) {
				if ($_GET['page']=='gallery') {
					//Редирект
					$url_add='';
					header("HTTP/1.1 301 Moved Permanently");
					if (!empty($_GET['view'])) $url_add.="&view=".$_GET['view'];
					if (!empty($_GET['cat'])) $url_add.="&cat=".$_GET['cat'];
					if (!empty($_GET['subcat'])) $url_add.="&subcat=".$_GET['subcat'];
					header("Location: http://images.nfsko.ru".(!empty($url_add) ? "/index.php?page=gallery".$url_add : ""));
					exit;
				} else if ($_GET['page']=='files') {
					//Редирект
					$url_add='';
					header("HTTP/1.1 301 Moved Permanently");
					if (!empty($_GET['cat'])) $url_add.="&cat=".$_GET['cat'];
					if (!empty($_GET['subcat'])) $url_add.="&subcat=".$_GET['subcat'];
					header("Location: http://files.nfsko.ru".(!empty($url_add) ? "/index.php?page=files".$url_add : ""));
					exit;
				}
			}
		}
	}
}