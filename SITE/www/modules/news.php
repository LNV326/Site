<?
function news_p($page) {
global $conf,$smarty,$sdk_info,$em;
    $smarty->cache_lifetime = 300;  //На 30 мин
    if (!$smarty->is_cached('modules/news_row.tpl',$page.$sdk_info['language'])) {
        //Создание строки для множества форумов
        $start=($conf['news_limit']*$page)-$conf['news_limit'];
//         $DB->query("SELECT p.author_id,p.author_name,p.post_date,p.post,t.tid,t.title,t.posts,t.description FROM ibf_topics t LEFT JOIN ibf_posts p ON (t.tid=p.topic_id) WHERE t.forum_id IN (".$conf['news_forum_id'].") and p.new_topic=1 ORDER BY t.tid DESC LIMIT " .$start.",".$conf['news_limit']);
        $repo = $em->getRepository('Entity\EntityForumTopics');
        $news = $repo->getTopicsForNews($conf['news_forum_id'], $start, $conf['news_limit']);      
//         while ($row = $DB->fetch_row()) {
        $last_news = array();
		foreach ($news as $row) {
			$postVal = $row->getPostsVal()->get(0);
			$post = $postVal->getPost();
            //Ставим всем внешним ссылкам <noindex> и rel="nofollow"
            if (!in_array(intval($row->getTid()), array(7998))) {
            	$post = preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru|files\.nfsko\.ru|images\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU', '<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $post);
            }
			//$row['post']=str_replace("&","&amp;",$row['post']);
			//$row['description']=str_replace("&","&amp;",$row['description']); 
			$post = str_replace("<br>","<br/>",$post);
			$post = str_replace(" alt='Прикреплённый рисунок'"," alt='Прикреплённый рисунок'/",$post);
			$post = str_replace("align=center","align=\"center\"",$post);
			$post = str_replace("align=right","align=\"right\"",$post);
			$post = str_replace("align=left","align=\"left\"",$post);
            //Создаём массив новостей                    
            $last_news[] = array(
                "topic_title"       => $row->getTitle(),
                "topic_id"          => $row->getTid(),
                "topic_time"        => strftime('%d.%m.%y', $postVal->getPostDate()),
                "author_name"       => $postVal->getAuthorName(),
                "author_id"         => $postVal->getAuthorId(),
                "content"           => $post,
                "comments_count"    => $row->getPosts(),
                "description"       => $row->getDescription()
            );        
        }
        //Если насобирали новости
        if (count($last_news)!=0) {    
            $smarty->assign('news', $last_news);
            $smarty->assign('news_page', $page);  
			$smarty->assign('user', $sdk_info[id]);          
            $data = $smarty->fetch("modules/news_row.tpl", $page.$sdk_info['language']);            
        } else {
            $smarty->cache_lifetime = -1;  //Навсегда
            $data = $smarty->fetch("modules/news_row_empty.tpl", $page.$sdk_info['language']);  
        }        
    } else $data = $smarty->fetch("modules/news_row.tpl", $page.$sdk_info['language']);       
	return $data;	
}

function last_p() {
    global $conf,$std,$smarty,$em;
    
    // $conf['active_ids'] ID разделов, которые вы хотите спрятать;
    // $conf['active_num'] Количество 'последних постов'
             
    //Последние сообщения
    $smarty->cache_lifetime = 10;  //На 10 секунд   
    if (!$smarty->is_cached('modules/news_last_posts.tpl')) {
//         $DB->query("SELECT last_poster_name,last_poster_id,last_post,tid,title FROM ibf_topics WHERE forum_id NOT IN (".$conf['active_ids'].") ORDER BY last_post DESC LIMIT 0,".$conf['active_num'].";" );
        $repo = $em->getRepository('Entity\EntityForumTopics');
    	$posts = $repo->getLastActiveTopics($conf['active_ids'], $conf['active_num']);
//         while($row = $DB->fetch_row()) {
    	$last_posts = array();
		foreach ($posts as $row) {
            //Обрезание
            $title = $row->getTitle();
            if (strlen($title)>30) 
                $title = mb_substr($title, 0, 27).'...';
            $author = $row->getLastPosterName();
            if (strlen($author)>18) 
                $author = mb_substr($author, 0, 15).'...';                
            $lastPostDate = $std->get_date($row->getLastPost(), 'LONG');
            //Обрезание
            $len=strlen($lastPostDate);
            $lastPostDate = mb_substr($lastPostDate, $len-5, $len);
            $last_posts[] = array(                
                'topic_id'      =>  $row->getTid(),
                'topic_title'   =>  $title,                             
                'author_id'     =>  $row->getLastPosterId(),
                'author_name'   =>  $author,
                'post_date'     =>  $lastPostDate);    
        }
        $smarty->assign('last_posts', $last_posts);
    }
    return $smarty->fetch("modules/news_last_posts.tpl");   
}

function last_f() {
    global $conf,$smarty,$em; 
    
    // $conf['active_num'] Количество 'последних файлов'
        
    //Файловый архив
        $smarty->cache_lifetime = 86400;  //На сутки   
        if (!$smarty->is_cached('modules/news_last_files.tpl')) {
//             $last_files = array();
//             $DB->query( "SELECT * FROM s_files_db WHERE `show`='Y' ORDER BY id DESC LIMIT ".$conf['active_num'].";");
//             while($row = $DB->fetch_row()) {
			$repo = $em->getRepository('Entity\EntitySFilesDb');
			$files = $repo->getLastAddedFiles($conf['active_num']);
			$last_files = array();
			foreach ($files as $row) {
                $len=mb_strlen($row->getName());
                if ($row->getLink() <> '1')
                    $len=$len-4;
                if ($len>33) 
                    $shortName=mb_substr($row->getName(), 0, 30).'...';
                else $shortName=mb_substr($row->getName(), 0, $len);                   
                $last_files[] = array(
                		'id' => $row->getId(),
                		'name' => $row->getName(),
                		'count' => $row->getCount(),
                		'shortname' => $shortName
                );
            }
            $smarty->assign('last_files', $last_files);
        }
    return $smarty->fetch("modules/news_last_files.tpl");   
}

//Функция вывода
function module_go() {
global $conf,$smarty,$nfs;

	if ($conf['active_on']==1){
        //Последние сообщения на форуме и последние файлы в архиве    
        $smarty->assign('last_posts', last_p());        
        $smarty->assign('last_files', last_f());  
	}
	if (isset($nfs->input['news']) and ($nfs->input['news'] > 0)) {
		$news_page=intval($nfs->input['news']);
	} else $news_page=1;
    
    $smarty->assign('last_news', news_p($news_page));
     
    $smarty->caching = false;  //Никогда
    $smarty->display("modules/news.tpl");
    $smarty->caching = 2;
        
    //Удаляем используемые в модуле Smarty-переменные  
    $smarty->clear_assign(array('last_news','last_posts','last_files','news','news_page'));  
}
?>