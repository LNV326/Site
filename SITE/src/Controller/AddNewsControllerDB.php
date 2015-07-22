<?php

namespace Controller;

use Controller\AbstractSiteController;

/**
 * 
 * @author Nikolay Lukyanov
 * 
 * @version 1.0
 * Refactoring from vertion of 2003 year, all HTML transfered to template file, show rules always for user
 *
 */
class AddNewsControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'AddNewsTemplate.php';
	
	protected function getData() {
		
		$this->_sdk_info['id'] = -1;
		$this->_sdk_info['name'] = 'Test';
		
		include "./admin/ad_editor/functions/global.php";
		$showForm = false;
		$showPreview = false;
		$notAuthorized = true;
		$errorEmptyTitle = false;
		if ($this->_sdk_info['id'] <> 0) {
			$notAuthorized = false;
			$showForm = true;
			$topicTitle = $this->_nfs->input['TopicTitle'];
			$postInBBCode = $_POST['Post'];
			$postInHTML = $this->_SDK->bbcode2html($_POST['Post']);
			$post = $this->_SDK->html2bbcode($this->_nfs->convert_html($_POST['Post']));
			$authorId = $this->_sdk_info[id];
			$authorName = $this->_sdk_info['name'];
			if ($this->_nfs->input['TopicDesc']=='') {
				$description='www.'.$this->_conf['site_url'];
			} else {
				$description=$this->_nfs->input['TopicDesc'];
			}
			if ($_POST['Post'] <> '' and $topicTitle == '')
				$errorEmptyTitle = true;
			
			if ($_POST['preview']) { //ПРЕДВАРИТЕЛЬНЫЙ ПРОСМОТР
				$showPreview = true;
			} else if ($_POST['Post'] <> '' and $topicTitle <> '') {  //СОЗДАНИЕ
				$this->_SDK->new_topic($this->_conf[usernews_forum_id], $topicTitle, $description, $postInBBCode);
				$showForm = false;
				$newTopicCreated = true;
			}
		}
		return array(
				'showForm' => $showForm,
				'showPreview' => $showPreview,
				'notAuthorized' => $notAuthorized,
				'errorEmptyTitle' => $errorEmptyTitle,
				'newTopicCreated' => $newTopicCreated,
				'topicTitle' => $topicTitle,
				'post' => $post,
				'postInBBCode' => $postInBBCode,
				'postInHTML' => $postInHTML,
				'description' => $description,
				'authorId' => $authorId,
				'authorName' => $authorName
		);
	}
}