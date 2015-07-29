<?php
namespace Controller;

use Controller\AbstractSiteController;

class FAQControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'FAQTemplate.php';
	
	//Вывод списка вопросов юзеру
	private function cat_list($cat) {
		$this->_DB->query("UPDATE s_faq_cat SET count=count+1 WHERE id=".$cat.";");
		$this->_DB->query("SELECT id,name,count FROM s_faq_cat WHERE id=".$cat." LIMIT 1");
		$catObj = $this->_DB->fetch_row();
		$faqs = array();
		$this->_DB->query("SELECT * FROM s_faq_db WHERE cat_id=".$cat." ORDER BY id DESC");
		while ($row = $this->_DB->fetch_row()) {
			$faqs[] = $row;
		}		
		return array(
				'page' => 'cat_list',
				'cat' => $catObj,
				'faqs' => $faqs				
		);
	}
	
	//Вывод помощи по форуму
	private function forum_help() {
		$help =& $this->_SDK->factory("help");
		if ($this->_nfs->input['help']) {
			$helpcat = intval($this->_nfs->input['help']);
			$info = $help->get_faq($helpcat);
		} else
			$faqs = $help->list_faqs();
		return array(
				'page' => 'forum_help',
				'helpcat' => $helpcat,
				'info' => $info,
				'faqs'=> $faqs				
		);
	}
	
	//Вывод категорий
	private function all_cat() {
		$cats = array();
		$this->_DB->query("SELECT id,name,info FROM s_faq_cat ORDER BY poz ASC");
		while ($row = $this->_DB->fetch_row()) {
			$cats[] = $row;
		}
		$this->_DB->query("SELECT count(id) as count FROM s_faq_db;");
		$row = $this->_DB->fetch_row();
		return array(
				'page' => 'all_cats',
				'cats' => $cats,
				'faqDBCount' => $row[count]
		);
	}
	
	protected function getData() {			
		// Тело самой программы - окучивание того, куда юзер влетел и выдача результатов ему на блюдечке!
		if (isset( $this->_nfs->input ['cat'] ) and ($this->_nfs->input ['cat'] == 'forum')) {
			return $this->forum_help();
		} else if (isset( $this->_nfs->input ['cat'] ) and ($this->_nfs->input ['cat'] != '') and ($this->_nfs->input ['cat'] != 'forum')) {
			$cat = intval( $this->_nfs->input ['cat'] );
			return $this->cat_list( $cat );
		} else {
			return $this->all_cat();
		}
	}
}