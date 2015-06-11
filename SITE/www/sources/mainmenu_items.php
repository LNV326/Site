<?php
use Repository\EntityForumChatonlineRep;
//Подсчёт кол-ва пользователей в чате!
$repo = $em->getRepository('Entity\EntityForumChatonline');
$count = $repo->getActiveSessionsCount();
//LNV's Mod (DELETE OLD STR FROM CHAT)
if (!empty($count)) 
	$repo->removeOldSessions();
//LNV's Mod's End

//Элементы меню
$main_menu=array();
$main_menu[] = array();
$main_menu[] = array("ru" => "Новости сайта", "en" => "Site News", "link" => "/", "image" => "rss.png", "image_title" => "RSS News", "image_link" => "http://www.".$conf[site_url]."/modules/rss.php");
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
?>