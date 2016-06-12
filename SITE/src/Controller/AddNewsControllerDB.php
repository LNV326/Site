<?php

namespace Controller;

use Controller\AbstractSiteController;

/**
 * 
 * @author Nikolay Lukyanov
 * 
 * @version 1.0 Tested 09/08/2015
 * 
 * Refactoring from old site engine version (year 2003). All HTML code transfered to template file.
 * 
 * TODO Maybe it's good to join this file and NewsController
 *
 */
class AddNewsControllerDB extends AbstractSiteController {
	
	protected $_templateName = 'AddNewsTemplate.php';
	
	protected function getData() {
				
		include "./admin/ad_editor/functions/global.php"; // TODO WTF? What this include and for what purpose?
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