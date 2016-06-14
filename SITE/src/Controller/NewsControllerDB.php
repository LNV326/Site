<?php

namespace Controller;

use Controller\AbstractSiteController;
use Template\TemplateEngineAdapter;

/**
 *
 * Refactoring from old site engine version (year 2003). All HTML code transfered to template file.
 * 
 * @author Nikolay Lukyanov
 *
 * @version 1.0 Tested 16/08/2015
 * @version 1.1 Tested 12/06/2016 Support TemplateEngineAdapter
 *
 */
class NewsControllerDB extends AbstractSiteController {
	
	protected $_templateName = 'modules/news.tpl';
	protected $_caching = 0; // Don't cache module output
	
	protected function getData() {
		// This controller has no cache but it consists of three independent prerendered parts
		// So the code below render each of three parst step by step
		
		if ($this->_conf['active_on']==1){
			//Последние сообщения на форуме и последние файлы в архиве
			// Get the last topics rendered html
			$templateName = 'modules/news_last_posts.tpl';
			$templateParams = array();
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, 2, 10) )
				$templateParams = $this->last_p();			
			$this->_templateParams['last_posts'] = $templateEngine->render( $templateName, $templateParams );			
			
			// Get the last files rendered html
			$templateName = 'modules/news_last_files.tpl';
			$templateParams = array();
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, 2, 86400) )
				$templateParams = $this->last_f();
			$this->_templateParams['last_files'] = $templateEngine->render( $templateName, $templateParams );
		}
		if (isset($this->_nfs->input['news']) and ($this->_nfs->input['news'] > 0)) {
			$news_page = intval($this->_nfs->input['news']);
		} else $news_page=1;
				
		// Get the last news rendered html
		$templateName = 'modules/news_row.tpl';
		$cacheId = $news_page.$this->_sdk_info['language'];
		$templateParams = array();
		$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
		if ( !$templateEngine->isCached($templateName, $cacheId, 2, 300) ) {
			$templateParams = $this->news_p($news_page);
			if (count($templateParams['news']) > 0)
				$this->_templateParams['last_news'] = $templateEngine->render( $templateName, $templateParams, $cacheId );
			else {
				// TODO Need to cache forever => cache_lifetime = -1
				$this->_templateParams['last_news'] = $templateEngine->render('modules/news_row_empty.tpl', $templateParams, $cacheId);
			}
		} else
			$this->_templateParams['last_news'] = $templateEngine->render( $templateName, $templateParams, $cacheId );
		return $this->_templateParams;
	}
		
	/**
	 * Page with news
	 * @param integer $page
	 */
	private function news_p($page) {
		//Создание строки для множества форумов
		$start=($this->_conf['news_limit']*$page)-$this->_conf['news_limit'];
		$this->_DB->query("SELECT p.author_id,p.author_name,p.post_date,p.post,t.tid,t.title,t.posts,t.description FROM ibf_topics t LEFT JOIN ibf_posts p ON (t.tid=p.topic_id) WHERE t.forum_id IN (".$this->_conf['news_forum_id'].") and p.new_topic=1 ORDER BY t.tid DESC LIMIT " .$start.",".$this->_conf['news_limit']);
		$news = array();
		while ($row = $this->_DB->fetch_row()) {
			//Ставим всем внешним ссылкам <noindex> и rel="nofollow"
			// 	            if (!in_array(intval($row['tid']), array(7998))) {
			// 	            	$row['post']=preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru|files\.nfsko\.ru|images\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU', '<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $row['post']);
			// 	            }
			$row['post']=OutLinkParse($row['post']);
			//$row['post']=str_replace("&","&amp;",$row['post']);
			//$row['description']=str_replace("&","&amp;",$row['description']);
			$row['post']=str_replace("<br>","<br/>",$row['post']);
			$row['post']=str_replace(" alt='Прикреплённый рисунок'"," alt='Прикреплённый рисунок'/",$row['post']);
			$row['post']=str_replace("align=center","align=\"center\"",$row['post']);
			$row['post']=str_replace("align=right","align=\"right\"",$row['post']);
			$row['post']=str_replace("align=left","align=\"left\"",$row['post']);
			//Создаём массив новостей
			$news[] = array(
					"topic_title"       => $row['title'],
					"topic_id"          => $row['tid'],
					"topic_time"        => strftime('%d.%m.%y', $row['post_date']),
					"author_name"       => $row['author_name'],
					"author_id"         => $row['author_id'],
					"content"           => $row['post'],
					"comments_count"    => $row['posts'],
					"description"       => $row['description'],
			);
		}
		//Если насобирали новости
		return array(
				'news' => $news,
				'news_page' => $page,
				'user' => $this->_sdk_info['id']
		);
	}
	
	/**
	 * Last posts in forum
	 */
	private function last_p() {
	
		// $conf['active_ids'] ID разделов, которые вы хотите спрятать;
		// $conf['active_num'] Количество 'последних постов'
		 
		//Последние сообщения
        $this->_DB->query("SELECT last_poster_name,last_poster_id,last_post,tid,title FROM ibf_topics WHERE forum_id NOT IN (".$this->_conf['active_ids'].") ORDER BY last_post DESC LIMIT 0,".$this->_conf['active_num'].";" );
        $last_posts = array();
        while($row = $this->_DB->fetch_row()) {
            //Обрезание
            if (strlen($row['title'])>30) 
                $row['title'] = mb_substr($row['title'], 0, 27).'...';
            if (strlen($row['last_poster_name'])>18) 
                $row['last_poster_name'] = mb_substr($row['last_poster_name'], 0, 15).'...';                
            $row['last_post'] = $this->_std->get_date($row['last_post'], 'LONG');
            //Обрезание
            $len=strlen($row['last_post']);
            $row['last_post']=mb_substr($row['last_post'], $len-5, $len);
            $last_posts[] = array(                
                'topic_id'      =>  $row['tid'],
                'topic_title'   =>  $row['title'],                             
                'author_id'     =>  $row['last_poster_id'],
                'author_name'   =>  $row['last_poster_name'],
                'post_date'     =>  $row['last_post']);    
        }
        return array(
        		'last_posts' => $last_posts
        );
	}
	
	/**
	 * Last files in archive
	 */
	private function last_f() {
	
		// $conf['active_num'] Количество 'последних файлов'
		$last_files = array();
		$this->_DB->query( "SELECT * FROM s_files_db WHERE `show`='Y' ORDER BY id DESC LIMIT ".$this->_conf['active_num'].";");
		while($row = $this->_DB->fetch_row()) {
			$len=mb_strlen($row['name']);
			if ($row['link'] <> '1')
				$len=$len-4;
			if ($len>33) 
				$row['shortname']=mb_substr($row['name'], 0, 30).'...';
			else $row['shortname']=mb_substr($row['name'], 0, $len);                   
			$last_files[] = $row;
		}
		return array(
				'last_files' => $last_files
				
		);
	}
	
}