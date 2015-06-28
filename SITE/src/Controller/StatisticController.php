<?php
namespace Controller;

class StatisticController extends AbstractSiteController {
	
	protected $_cacheLifetime = 600; // 10 minutes
	protected $_templateName = 'modules/stats.tpl';
	
	public function __construct($em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs) {
		parent::__construct($em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs);
	}
	
	
	protected function getData() {
		$this->_ibforums->base_url = $this->_INFO ['board_url'] . '/index.php';
		$em = $this->_em;
		$repoStats = $em->getRepository( 'Entity\EntityForumStats' );
		$stats = $repoStats->findOneBy( [ ] );
		$total_posts = $stats->getTotalReplies() + $stats->getTotalTopics();
		
		$repoSessions = $em->getRepository( 'Entity\EntityForumSessions' );
		$members = count( $repoSessions->getActiveUserSessions() );
		$guests = count( $repoSessions->getActiveGuestSessions() );
		
		$repoTopic = $em->getRepository( 'Entity\EntityForumTopics' );
		// TODO code below has no view in template engine
		// $out = $repoTopic->getLastActiveTopic();
		// $thread_url = "<a href=\"".$ibforums->base_url. "?showtopic=".$out->getTid()."&view=getnewpost\" target=\"_blank\">".$out->getTitle()."</a>";
		// $user_url = "<a href=\"".$ibforums->base_url. "?showuser=".$out->getLastPosterId()."\" target=\"_blank\">".$out->getLastPosterName()."</a>";
		$most_time = $this->_std->get_date( $stats->getMostDate(), 'SHORT' );
		$last_members = "<a href=\"" . $this->_ibforums->base_url . "?showuser=" . $stats->getLastMemId() . "\" target=\"_blank\">" . $stats->getLastMemName() . "</a>";
		
		$popposts = $repoTopic->getMostPostedTopic();
		$posts = $popposts->getPosts();
		$poppostsurl = "<a href=\"" . $this->_ibforums->base_url . "?showtopic=" . $popposts->getTid() . "&view=getnewpost\" target=\"_blank\">" . $popposts->getTitle() . "</a>";
		
		$popviews = $repoTopic->getMostViewedTopic();
		$views = $popviews->getViews();
		$popviewsurl = "<a href=\"" . $this->_ibforums->base_url . "?showtopic=" . $popviews->getTid() . "&view=getnewpost\" target=\"_blank\">" . $popviews->getTitle() . "</a>";
		
		$repoMember = $em->getRepository( 'Entity\EntityForumMembers' );
		$topmember = $repoMember->getMostPosterMember();
		$topposts = $topmember->getPosts();
		$topmemberurl = "<a href=\"" . $this->_ibforums->base_url . "?showuser=" . $topmember->getId() . "\" target=\"_blank\">" . $topmember->getName() . "</a>";
		
		$poll = $repoTopic->getTopicWithLastActivePoll();
		$poll_url = "<a href=\"" . $this->_ibforums->base_url . "?showtopic=" . $poll->getTid() . "&view=getnewpost\" target=\"_blank\">" . $poll->getTitle() . "</a>";
		
		// FILES категории
		$files_cats = array (
				'cnt' => $em->getRepository( 'Entity\EntitySFilesCat' )->getCount() 
		);
		// FILES подкатегории
		$files_subcats = array (
				'cnt' => $em->getRepository( 'Entity\EntitySFilesSubcat' )->getCount() 
		);
		// FILES файлы
		$files_count = array (
				'cnt' => $em->getRepository( 'Entity\EntitySFilesDb' )->getCountForShow() 
		);
		
		// GALLERY категории
		$gallery_cats = array (
				'cnt' => $em->getRepository( 'Entity\EntitySGalleryCat' )->getCount() 
		);
		// FILES подкатегории
		$gallery_subcats = array (
				'cnt' => $em->getRepository( 'Entity\EntitySGallerySubcat' )->getCount() 
		);
		// FILES файлы
		$gallery_count = array (
				'cnt' => $em->getRepository( 'Entity\EntitySGalleryImages' )->getCountForShow() 
		);
		
		// PAGES штук
		$repoPages = $em->getRepository( 'Entity\EntitySPages' );
		$pages = $repoPages->getAllSortedByPopularity();
		$pages_count = count( $pages );
		if ($pages_count > 0) {
			$max_page_id = $pages [0]->getName();
			$max_page_name = $pages [0]->getTitle();
			$max_page_count = $pages [0]->getCounts();
		}
		
		// TODO It's told on statistic page that $pages_count is without modules count =)
		// $DB->query("SELECT count(id) as cnt FROM s_modules");
		// $modules_count = $DB->fetch_row();
		// $pages_count=$pages_count+$modules_count[cnt];
		
		// FAQ Подсчёт кол-ва категорий
		$repoFaqCat = $em->getRepository( 'Entity\EntitySFaqCat' );
		$faqCategories = $repoFaqCat->getCategoriesByPopularity();
		$faq_cats = count( $faqCategories );
		if ($faq_cats > 0) {
			$max_id = $faqCategories [0]->getId();
			$max_name = $faqCategories [0]->getName();
			$max_count = $faqCategories [0]->getCount();
		}
		
		// FAQ категории
		$faq_questions = array (
				'cnt' => $em->getRepository( 'Entity\EntitySFaqDb' )->getCount() 
		);
		
		// 10 скачиваемых
		$repoFiles = $em->getRepository( 'Entity\EntitySFilesDb' );
		$files = $repoFiles->getMostPopularFiles( 10 );
		foreach ( $files as $row ) {
			if ($row->getLink() != '1') {
				$name = file_name( $row->getName() );
			} else {
				$name = $row->getName();
			}
			$top_10_downloads .= ' <a href="http://files.' . $this->_conf ['site_url'] . '/download.php?go=' . $row->getId() . '" title="Скачать">' . $name . '</a> - (' . $row->getCount() . ')<br>';
		}
		// 10 новых
		$files = $repoFiles->getLastAddedFiles( 10 );
		foreach ( $files as $row ) {
			if ($row->getLink() != '1') {
				$name = file_name( $row->getName() );
			} else {
				$name = $row->getName();
			}
			$top_10_new .= ' <a href="http://files.' . $this->_conf ['site_url'] . '/download.php?go=' . $row->getId() . '" title="Скачать">' . $name . '</a> - (' . $row->getCount() . ')<br>';
		}
		
		$this->_smarty->assign( 'stat', array (
				'total_posts' => $total_posts,
				'poppostsurl' => $poppostsurl,
				'popviewsurl' => $popviewsurl,
				'topposts' => $topposts,
				'topmemberurl' => $topmemberurl,
				'poll_url' => $poll_url,
				'topics' => $stats->getTotalTopics(),
				'replies' => $stats->getTotalReplies(),
				'most_time' => $most_time,
				'most_count' => $stats->getMostCount(),
				'all_members' => $stats->getMemCount(),
				'last_members' => $last_members,
				'members' => $members,
				'guests' => $guests,
				'posts' => $posts,
				'views' => $views,
				'pages_count' => $pages_count,
				'max_page_id' => $max_page_id,
				'max_page_name' => $max_page_name,
				'max_page_count' => $max_page_count,
				'files_count' => $files_count,
				'files_cats' => $files_cats,
				'files_subcats' => $files_subcats,
				'gallery_count' => $gallery_count,
				'gallery_cats' => $gallery_cats,
				'gallery_subcats' => $gallery_subcats,
				'faq_questions' => $faq_questions,
				'faq_cats' => $faq_cats,
				'max_id' => $max_id,
				'max_name' => $max_name,
				'top_10_new' => $top_10_new,
				'top_10_downloads' => $top_10_downloads,
				'max_count' => $max_count 
		) );
	}
}