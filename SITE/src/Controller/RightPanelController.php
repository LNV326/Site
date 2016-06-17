<?php

namespace Controller;

use Service\GalleryService;
use Service\FilesStoreService;
use Template\TemplateEngineAdapter;
/**
 * Refactoring from old site engine version (year 2003).
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0
 *
 */
class RightPanelController extends AbstractComplexController  {
	
	protected $_templateName = './../mainmenu_right.php';
	
	protected $_CONFIG = array(
			'renderLoginPanel',
			'renderWhoIsOnlinePanel',
			'renderModeratorPanel',
			'renderRandomScreenshot',
			'renderSearchPanel',
			'renderSubprojectsPanel',
			'renderTimerPanel',
			'renderOthersPanel'			
	);
	
	private $_relatedTemplatePath = '../../../../src/Template/1/ComplexController/RightPanelController/';
	
	protected function renderLoginPanel() { 
		//Поле авторизации
		if (($this->_conf['menur_login']==1) and (!$this->_SDK->is_loggedin())) {
			return $this->renderTemplate(
					$this->_relatedTemplatePath.'block-login.tpl',
					null, null,
					TemplateEngineAdapter::TIME_EXPIRE_0);
		}
	}

	protected function renderWhoIsOnlinePanel() {		
		//Кто онлайн
		if ($this->_conf['menur_online'] == 1) {
			return $this->renderTemplate(
					$this->_relatedTemplatePath.'block-online.tpl',
					$this, 'getDataWhoIsOnlinePanel',
// 					function() {return $this->getDataWhoIsOnlinePanel();},
					TemplateEngineAdapter::TIME_EXPIRE_10_SEC);
		}
	}
	
	protected function renderModeratorPanel() {
		//Панель модератора
		if (($this->_sdk_info[id]==$this->_admins['root']) or ($this->_SDK->is_small_siteadmin($this->_sdk_info['id']) == TRUE) or ($this->_SDK->is_full_siteadmin($this->_sdk_info['id']) == TRUE)) {
			return $this->renderTemplate(
					$this->_relatedTemplatePath.'block-modcp.tpl',
					$this, 'getDataModeratorPanel',
// 					function() {return $this->getDataModeratorPanel();},
					TemplateEngineAdapter::TIME_EXPIRE_1_MIN);
		}
	}
		
	protected function renderRandomScreenshot() {
		//Случайный скриншот
		if ($this->_conf['menur_gallery'] == 1) {
			$cacheId = rand(1,5);	// Let us caching 5 variants of random screen in the same time
			return $this->renderTemplate(
					$this->_relatedTemplatePath.'block-rndscreen.tpl',
					$this, 'getDataRandomScreenshot',
// 					function() {return $this->getDataRandomScreenshot();},
					TemplateEngineAdapter::TIME_EXPIRE_1_MIN,
					$cacheId);
		}
	}
	
	protected function renderSearchPanel() {
		//Поле поиска
		if ($this->_conf['menur_search'] == 1) {
			return $this->renderTemplate(
					$this->_relatedTemplatePath.'block-search.tpl',
					null, null,
					TemplateEngineAdapter::TIME_EXPIRE_0);
		}
	}
	
	protected function renderSubprojectsPanel() {
		//Поле доп. проектов
		return $this->renderTemplate(
				$this->_relatedTemplatePath.'block-projects.tpl',
				null, null,
				TemplateEngineAdapter::TIME_EXPIRE_0);
	}
	
	protected function renderTimerPanel() {
		//Таймер
		if ($this->_conf['menur_time'] == 1) {
			return $this->renderTemplate(
					$this->_relatedTemplatePath.'timer.tpl',
					null, null,
					TemplateEngineAdapter::TIME_EXPIRE_1_DAY);
		}
	}
	
	protected function renderOthersPanel() {
		//Остальное
		return $this->renderTemplate(
				$this->_relatedTemplatePath.'other.tpl',
				null, null,
				TemplateEngineAdapter::TIME_EXPIRE_0);
	}
	
	//====
	public function getDataWhoIsOnlinePanel() {
		$to_echo = array ();
		$this->_DB->query( "SELECT s.member_name, s.member_id FROM ibf_sessions s WHERE s.member_id <> 0 AND s.running_time > " . (time() - 900) . " ORDER BY 'running_time' DESC LIMIT 0," . $this->_conf ['online_num'] . ";" );
		while ( $out = $this->_DB->fetch_row() ) {
			$to_echo [] = "<a href='/forum/index.php?showuser=" . $out ['member_id'] . "' target='_blank'>" . $out ['member_name'] . "</a>";
		}
		$to_echo = implode( ", ", $to_echo );
		$this->_DB->query( "SELECT count(member_id) as count FROM ibf_sessions WHERE member_id <> 0 AND running_time > " . (time() - 900) . " LIMIT 0,1" );
		$row = $this->_DB->fetch_row();
		if ($row ['count'] > $this->_conf ['online_num'])
			$to_echo .= '...';
		$mem_count = $row['count'];
		$this->_DB->query( "SELECT count(member_id) as count FROM ibf_sessions WHERE member_id = 0 AND running_time > " . (time() - 900) . " LIMIT 0,1" );
		$row = $this->_DB->fetch_row();
		$que_count = $row ['count'];
		return array(
				'mem_count' => $mem_count,
				'mem_str' => $to_echo,
				'que_count' => $que_count
		);
	}
	
	public function getDataModeratorPanel() {
		//Панель модератора
		GalleryService::init($this->_conf, $this->_DB);
		FilesStoreService::init($this->_conf, $this->_DB);
		return array(
				'images_count' => GalleryService::getCountForReview(),
				'files_count' => FilesStoreService::getCountForReview()
		);
	}
	
	public function getDataRandomScreenshot() {
		//Случайный скриншот
		GalleryService::init($this->_conf, $this->_DB);
		$imageInfo = GalleryService::getRandomImage();
		return array(
				'image' => $imageInfo['image'],
				'subcat_row' => $imageInfo['subcat'],
				'size_px' => $imageInfo['size_px']
		);
	}
}