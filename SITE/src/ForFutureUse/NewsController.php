<?php

namespace Controller;

use Controller\AbstractSiteController;

class NewsController extends AbstractSiteController {
	
	protected $_templateName = 'modules/news.tpl';
	protected $_caching = 0; // Don't cache module output
	
	protected function getData() {
		if ($this->_conf['active_on']==1){
			//Последние сообщения на форуме и последние файлы в архиве
			$this->_smarty->assign('last_posts', $this->last_p());
			$this->_smarty->assign('last_files', $this->last_f());
		}
		if (isset($this->_nfs->input['news']) and ($this->_nfs->input['news'] > 0)) {
			$news_page = intval($this->_nfs->input['news']);
		} else $news_page=1;
		
		$this->_smarty->assign('last_news', $this->news_p($news_page));	
	}
	
	protected function postIndexHook() {
		//Удаляем используемые в модуле Smarty-переменные
		$this->_smarty->clear_assign(array('last_news','last_posts','last_files','news','news_page'));
	}
	
	/**
	 * Page with news
	 * @param integer $page
	 */
	private function news_p($page) {
		$this->_smarty->cache_lifetime = 300;  //На 30 мин
		if (!$this->_smarty->is_cached('modules/news_row.tpl',$page.$this->sdk_info['language'])) {
			//Создание строки для множества форумов
			$start=($this->_conf['news_limit']*$page)-$this->_conf['news_limit'];
			$repo = $this->_em->getRepository('Entity\EntityForumTopics');
			$news = $repo->getTopicsForNews($this->_conf['news_forum_id'], $start, $this->_conf['news_limit']);
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
				$this->_smarty->assign('news', $last_news);
				$this->_smarty->assign('news_page', $page);
				$this->_smarty->assign('user', $this->sdk_info[id]);
				$data = $this->_smarty->fetch("modules/news_row.tpl", $page.$this->sdk_info['language']);
			} else {
				$this->_smarty->cache_lifetime = -1;  //Навсегда
				$data = $this->_smarty->fetch("modules/news_row_empty.tpl", $page.$this->sdk_info['language']);
			}
		} else $data = $this->_smarty->fetch("modules/news_row.tpl", $page.$this->sdk_info['language']);
		return $data;
	}
	
	/**
	 * Last posts in forum
	 */
	private function last_p() {
	
		// $conf['active_ids'] ID разделов, которые вы хотите спрятать;
		// $conf['active_num'] Количество 'последних постов'
		 
				//Последние сообщения
		$this->_smarty->cache_lifetime = 10;  //На 10 секунд
		if (!$this->_smarty->is_cached('modules/news_last_posts.tpl')) {
			$repo = $this->_em->getRepository('Entity\EntityForumTopics');
			$posts = $repo->getLastActiveTopics($this->_conf['active_ids'], $this->_conf['active_num']);
			$last_posts = array();
			foreach ($posts as $row) {
				//Обрезание
				$title = $row->getTitle();
				if (strlen($title)>30)
					$title = mb_substr($title, 0, 27).'...';
				$author = $row->getLastPosterName();
				if (strlen($author)>18)
					$author = mb_substr($author, 0, 15).'...';
				$lastPostDate = $this->_std->get_date($row->getLastPost(), 'LONG');
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
			$this->_smarty->assign('last_posts', $last_posts);
		}
		return $this->_smarty->fetch("modules/news_last_posts.tpl");
	}
	
	/**
	 * Last files in archive
	 */
	private function last_f() {
	
		// $conf['active_num'] Количество 'последних файлов'
	
		//Файловый архив
		$this->_smarty->cache_lifetime = 86400;  //На сутки
		if (!$this->_smarty->is_cached('modules/news_last_files.tpl')) {
			$repo = $this->_em->getRepository('Entity\EntitySFilesDb');
			$files = $repo->getLastAddedFiles($this->_conf['active_num']);
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
			$this->_smarty->assign('last_files', $last_files);
		}
		return $this->_smarty->fetch("modules/news_last_files.tpl");
	}
	
}