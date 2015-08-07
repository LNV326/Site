<?php
namespace Controller;

/**
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0 Tested 09/08/2015
 *
 * Refactoring from old site engine version (year 2003). All HTML code transfered to template file.
 *
 */
class StatisticControllerDB extends AbstractSiteController {
	
	protected $_cacheLifetime = 600; // 10 minutes
	protected $_templateName = 'modules/stats.tpl';
	
	protected function getData() {
		$this->_ibforums->base_url = $this->_INFO['board_url'].'/index.php';
		
		$this->_DB->query("SELECT * FROM ibf_stats");
		$stats = $this->_DB->fetch_row();
		$total_posts = $stats['TOTAL_REPLIES']+$stats['TOTAL_TOPICS'];
		
		$this->_DB->query( "SELECT s.member_name, s.member_id, s.running_time
	FROM ibf_sessions s
	WHERE s.login_type != '1' AND s.running_time > " . (time() - 900) . " ORDER BY `running_time` DESC;");
		$members=0;
		$guests=0;
		while( $out = $this->_DB->fetch_row()) {
			if ($out['member_id']==0) {
				$guests+=1;
			} else {
				$members+=1;
			}
		}
		
		$this->_DB->query( "SELECT tid, last_poster_name, last_post, forum_id, title, last_poster_id FROM  ibf_topics ORDER BY last_post DESC LIMIT 1" );
		$out = $this->_DB->fetch_row();
// 		$thread_url = "<a href=\"".$this->_ibforums->base_url. "?showtopic=".$out['tid']."&view=getnewpost\" target=\"_blank\">".$out['title']."</a>";
// 		$user_url = "<a href=\"".$this->_ibforums->base_url. "?showuser=".$out['last_poster_id']."\" target=\"_blank\">".$out['last_poster_name']."</a>";
		$most_time = $this->_std->get_date( $stats['MOST_DATE'], 'SHORT' );
		$last_members = "<a href=\"".$this->_ibforums->base_url. "?showuser=".$stats['LAST_MEM_ID']."\" target=\"_blank\">".$stats['LAST_MEM_NAME']."</a>";
		
		$this->_DB->query("SELECT title, tid, posts, forum_id FROM ibf_topics ORDER BY posts DESC LIMIT 1" );
		$popposts = $this->_DB->fetch_row();
		$posts = $popposts['posts'];
		$poppostsurl = "<a href=\"".$this->_ibforums->base_url. "?showtopic=".$popposts['tid']."&view=getnewpost\" target=\"_blank\">".$popposts['title']."</a>";
		
		$this->_DB->query("SELECT title, tid, views, forum_id FROM ibf_topics ORDER BY views DESC LIMIT 1" );
		$popviews = $this->_DB->fetch_row();
		$views = $popviews['views'];
		$popviewsurl = "<a href=\"".$this->_ibforums->base_url. "?showtopic=".$popviews['tid']."&view=getnewpost\" target=\"_blank\">".$popviews['title']."</a>";
		
		$this->_DB->query("SELECT name, id, posts FROM ibf_members ORDER BY posts DESC LIMIT 1");
		$topmember = $this->_DB->fetch_row();
		$topposts = $topmember['posts'];
		$topmemberurl =   "<a href=\"".$this->_ibforums->base_url. "?showuser=".$topmember['id']."\" target=\"_blank\">".$topmember['name']."</a>";
		
		$this->_DB->query("SELECT start_date, tid FROM ibf_polls ORDER BY start_date DESC LIMIT 1");
		$polltid = $this->_DB->fetch_row();
		$this->_DB->query("SELECT tid, forum_id, title FROM ibf_topics WHERE tid='".$polltid['tid']."'");
		$poll = $this->_DB->fetch_row();
		$poll_url = "<a href=\"".$this->_ibforums->base_url. "?showtopic=".$poll['tid']."&view=getnewpost\" target=\"_blank\">".$poll['title']."</a>";
		
		//FILES категории
		$this->_DB->query("SELECT count(id) as cnt FROM s_files_cat");
		$files_cats = $this->_DB->fetch_row();
		//FILES подкатегории
		$this->_DB->query("SELECT count(id) as cnt FROM s_files_subcat");
		$files_subcats = $this->_DB->fetch_row();
		//FILES файлы
		$this->_DB->query("SELECT count(id) as cnt FROM s_files_db WHERE `show`='Y'");
		$files_count = $this->_DB->fetch_row();
		
		//GALLERY категории
		$this->_DB->query("SELECT count(id) as cnt FROM s_gallery_cat");
		$gallery_cats = $this->_DB->fetch_row();
		//FILES подкатегории
		$this->_DB->query("SELECT count(id) as cnt FROM s_gallery_subcat");
		$gallery_subcats = $this->_DB->fetch_row();
		//FILES файлы
		$this->_DB->query("SELECT count(id) as cnt FROM s_gallery_images");
		$gallery_count = $this->_DB->fetch_row();
		
		//PAGES штук
		$this->_DB->query("SELECT name,title,counts FROM s_pages");
		$max_page_count = 0;
		while ($row = $this->_DB->fetch_row()) {
			$pages_count+=1;
			if ($row[counts]>$max_page_count) {
				$max_page_id=$row[name];
				$max_page_name=$row[title];
				$max_page_count=$row[counts];
			}
		}
		$this->_DB->query("SELECT count(id) as cnt FROM s_modules");
		$modules_count = $this->_DB->fetch_row();
		$pages_count=$pages_count+$modules_count[cnt];
		
		//FAQ Подсчёт кол-ва категорий
		$max_count = 0;
		$this->_DB->query("SELECT id,name,count FROM s_faq_cat");
		while ($row = $this->_DB->fetch_row()) {
			$faq_cats+=1;
			if ($row[count]>$max_count) {
				$max_id=$row[id];
				$max_name=$row[name];
				$max_count=$row[count];
			}
		}
		//FAQ категории
		$this->_DB->query("SELECT count(id) as cnt FROM s_faq_db");
		$faq_questions = $this->_DB->fetch_row();
		
		//10 скачиваемых
		$result = $this->_DB->query("SELECT * FROM s_files_db WHERE `show`='Y' ORDER BY count DESC LIMIT 10");
		while ($row = $this->_DB->fetch_row($result)) {
			if ($row[link]<>'1') {
				$name=file_name($row[name]);
			} else {
				$name=$row[name];
			}
			$top_10_downloads.=' <a href="http://files.'.$this->_conf['site_url'].'/download.php?go='.$row[id].'" title="Скачать">'.$name.'</a> - ('.$row[count].')<br>';
		}
		//10 новых
		$result = $this->_DB->query("SELECT * FROM s_files_db WHERE `show`='Y' ORDER BY id DESC LIMIT 10");
		while ($row = $this->_DB->fetch_row($result)) {
			if ($row[link]<>'1') {
				$name=file_name($row[name]);
			} else {
				$name=$row[name];
			}
			$top_10_new.=' <a href="http://files.'.$this->_conf['site_url'].'/download.php?go='.$row[id].'" title="Скачать">'.$name.'</a> - ('.$row[count].')<br>';
		}
		
		$this->_smarty->assign('stat', array(
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
	}
}