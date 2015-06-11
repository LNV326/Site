<?
global $INFO, $em, $DB, $smarty, $std;
$smarty->cache_lifetime = 100;  //На 10 мин
if (!$smarty->is_cached('modules/stats.tpl')) {
	
	
	$ibforums->base_url = $INFO['board_url'].'/index.php';
	
	$DB->query("SELECT * FROM ibf_stats");
	$stats = $DB->fetch_row();
	$total_posts = $stats['TOTAL_REPLIES']+$stats['TOTAL_TOPICS'];
	
	$repoSessions = $em->getRepository('Entity\EntityForumSessions');
	$members = count($repoSessions->getActiveUserSessions());
	$guests = count($repoSessions->getActiveGuestSessions());
	
	$DB->query( "SELECT tid, last_poster_name, last_post, forum_id, title, last_poster_id FROM  ibf_topics ORDER BY last_post DESC LIMIT 1" );
	$out = $DB->fetch_row();
	$thread_url = "<a href=\"".$ibforums->base_url. "?showtopic=".$out['tid']."&view=getnewpost\" target=\"_blank\">".$out['title']."</a>";
	$user_url = "<a href=\"".$ibforums->base_url. "?showuser=".$out['last_poster_id']."\" target=\"_blank\">".$out['last_poster_name']."</a>";
	$most_time = $std->get_date( $stats['MOST_DATE'], 'SHORT' );
	$last_members = "<a href=\"".$ibforums->base_url. "?showuser=".$stats['LAST_MEM_ID']."\" target=\"_blank\">".$stats['LAST_MEM_NAME']."</a>";
	
	$DB->query("SELECT title, tid, posts, forum_id FROM ibf_topics ORDER BY posts DESC LIMIT 1" );
	$popposts = $DB->fetch_row();
	$posts = $popposts['posts'];
	$poppostsurl = "<a href=\"".$ibforums->base_url. "?showtopic=".$popposts['tid']."&view=getnewpost\" target=\"_blank\">".$popposts['title']."</a>"; 
	 
	$DB->query("SELECT title, tid, views, forum_id FROM ibf_topics ORDER BY views DESC LIMIT 1" );
	$popviews = $DB->fetch_row();
	$views = $popviews['views'];
	$popviewsurl = "<a href=\"".$ibforums->base_url. "?showtopic=".$popviews['tid']."&view=getnewpost\" target=\"_blank\">".$popviews['title']."</a>"; 
	
	$DB->query("SELECT name, id, posts FROM ibf_members ORDER BY posts DESC LIMIT 1");
	$topmember = $DB->fetch_row();
	$topposts = $topmember['posts'];
	$topmemberurl =   "<a href=\"".$ibforums->base_url. "?showuser=".$topmember['id']."\" target=\"_blank\">".$topmember['name']."</a>";
	

	$repoTopic = $em->getRepository('Entity\EntityForumTopics');
	$poll = $repoTopic->getTopicWithLastActivePoll();
	$poll_url = "<a href=\"".$ibforums->base_url. "?showtopic=".$poll->getTid()."&view=getnewpost\" target=\"_blank\">".$poll->getTitle()."</a>";
	 
	//FILES категории
	$DB->query("SELECT count(id) as cnt FROM s_files_cat");
	$files_cats = $DB->fetch_row();
	//FILES подкатегории
	$DB->query("SELECT count(id) as cnt FROM s_files_subcat");
	$files_subcats = $DB->fetch_row();
	//FILES файлы
	$files_count = $em->getRepository('Entity\EntitySFilesDb')->getCountForShow();
	
	//GALLERY категории
	$DB->query("SELECT count(id) as cnt FROM s_gallery_cat");
	$gallery_cats = $DB->fetch_row();
	//FILES подкатегории
	$DB->query("SELECT count(id) as cnt FROM s_gallery_subcat");
	$gallery_subcats = $DB->fetch_row();
	//FILES файлы
	$gallery_count = $em->getRepository('Entity\EntitySGalleryImages')->getCountForShow();
	
	//PAGES штук
	$repoPages = $em->getRepository('Entity\EntitySPages');
	$pages = $repoPages->getPagesByPopularity();
	$pages_count = count($pages);
	if ($pages_count > 0) {
		$max_page_id = $pages[0]->getName();
		$max_page_name = $pages[0]->getTitle();
		$max_page_count = $pages[0]->getCounts();
	}

	//TODO It's told on statistic page that $pages_count is without modules count =)
// 	$DB->query("SELECT count(id) as cnt FROM s_modules");
// 	$modules_count = $DB->fetch_row();
// 	$pages_count=$pages_count+$modules_count[cnt];
	
	//FAQ Подсчёт кол-ва категорий
	$repoFaqCat = $em->getRepository('Entity\EntitySFaqCat');
	$faqCategories = $repoFaqCat->getCategoriesByPopularity();
	$faq_cats = count($faqCategories);
	if ($faq_cats > 0) {
		$max_id = $faqCategories[0]->getId();
		$max_name = $faqCategories[0]->getName();
		$max_count = $faqCategories[0]->getCount();
	}

	//FAQ категории
	$DB->query("SELECT count(id) as cnt FROM s_faq_db");
	$faq_questions = $DB->fetch_row();
	
	//10 скачиваемых
	$repoFiles = $em->getRepository('Entity\EntitySFilesDb');
	$files = $repoFiles->getMostPopularFiles(10);
	foreach ($files as $row) {
		if ($row->getLink()<>'1') {
			$name=file_name($row->getName());
		} else {
			$name=$row->getName();
		}
		$top_10_downloads.=' <a href="http://files.'.$conf['site_url'].'/download.php?go='.$row->getId().'" title="Скачать">'.$name.'</a> - ('.$row->getCount().')<br>';
	}
	//10 новых
	$files = $repoFiles->getLastAddedFiles(10);
	foreach ($files as $row) {
		if ($row->getLink()<>'1') {
			$name=file_name($row->getName());
		} else {
			$name=$row->getName();
		}
		$top_10_new.=' <a href="http://files.'.$conf['site_url'].'/download.php?go='.$row->getId().'" title="Скачать">'.$name.'</a> - ('.$row->getCount().')<br>';
	}
	
	$smarty->assign('stat', array(
		'total_posts'	=> $total_posts,
		'poppostsurl'	=> $poppostsurl,
		'popviewsurl'	=> $popviewsurl,
		'topposts'	=> $topposts,
		'topmemberurl'	=> $topmemberurl,
		'poll_url'	=> $poll_url,
		'topics'	=> $stats['TOTAL_TOPICS'],
		'replies'	=> $stats['TOTAL_REPLIES'],
		'most_time' 	=> $most_time,
		'most_count'	=> $stats['MOST_COUNT'],
		'all_members'	=> $stats['MEM_COUNT'],
		'last_members'	=> $last_members,
		'members'	=> $members,
		'guests'	=> $guests,
		'posts'		=> $posts,
		'views'		=> $views,	
		'pages_count'		=> $pages_count,
		'max_page_id'		=> $max_page_id,
		'max_page_name'		=> $max_page_name,
		'max_page_count'		=> $max_page_count,
		'files_count'		=> $files_count,
		'files_cats'		=> $files_cats,
		'files_subcats'		=> $files_subcats,
		'gallery_count'		=> $gallery_count,
		'gallery_cats'		=> $gallery_cats,
		'gallery_subcats'		=> $gallery_subcats,
		'faq_questions'		=> $faq_questions,
		'faq_cats'		=> $faq_cats,
		'max_id'		=> $max_id,
		'max_name'		=> $max_name,
		'top_10_new'		=> $top_10_new,
		'top_10_downloads'		=> $top_10_downloads,
		'max_count'		=> $max_count
	));
	$smarty->display("modules/stats.tpl");

} else $smarty->display("modules/stats.tpl");
?>