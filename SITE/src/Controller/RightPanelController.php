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
	
	protected $_templateName = '.php';
	protected $_caching = 0; // Don't cache module output
	
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
			$templateName = $this->_relatedTemplatePath.'block-login.tpl';
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, -1, 0) ) {}
			$templateEngine->display( $templateName, null );
		}
	}

	protected function renderWhoIsOnlinePanel() {		
		//Кто онлайн
		if ($this->_conf['menur_online'] == 1){
			$templateName = $this->_relatedTemplatePath.'block-online.tpl';
			$templateParams = array();
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, 2, 60) ) {
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
				$templateParams['mem_count'] = $row['count'];
				$templateParams['mem_str'] = $to_echo;
				$this->_DB->query( "SELECT count(member_id) as count FROM ibf_sessions WHERE member_id = 0 AND running_time > " . (time() - 900) . " LIMIT 0,1" );
				$row = $this->_DB->fetch_row();
				$templateParams['que_count'] = $row ['count'];
			}		
			$templateEngine->display( $templateName, $templateParams );
		}
	}
	
	protected function renderModeratorPanel() {
		//Панель модератора
		if (($this->_sdk_info[id]==$this->_admins['root']) or ($this->_SDK->is_small_siteadmin($this->_sdk_info['id']) == TRUE) or ($this->_SDK->is_full_siteadmin($this->_sdk_info['id']) == TRUE)) {
			$templateName = $this->_relatedTemplatePath.'block-modcp.tpl';
			$templateParams = array();
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, 2, 10) ) {
				GalleryService::init($this->_conf, $this->_DB);
				$templateParams['images_count'] = GalleryService::getCountForReview();
				FilesStoreService::init($this->_conf, $this->_DB);
				$templateParams['files_count'] = FilesStoreService::getCountForReview();
			}
			$templateEngine->display( $templateName, $templateParams );
		}
	}
		
	protected function renderRandomScreenshot() {
		//Случайный скриншот
		if ($this->_conf['menur_gallery'] == 1){
			$templateName = $this->_relatedTemplatePath.'block-rndscreen.tpl';
			$templateParams = array();
			$vid = rand(1,5);
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, $vid, 2, 60) ) {
				GalleryService::init($this->_conf, $this->_DB);
				$imageInfo = GalleryService::getRandomImage();
				$templateParams['image'] = $imageInfo['image'];
				$templateParams['subcat_row'] = $imageInfo['subcat'];
				$templateParams['size_px'] = $imageInfo['size_px'];
			}
			$templateEngine->display( $templateName, $templateParams, $vid );
		}
	}
	
	protected function renderSearchPanel() {
		//Поле поиска
		if ($this->_conf['menur_search'] == 1) {
			$templateName = $this->_relatedTemplatePath.'block-search.tpl';
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, -1, 0) ) {}
			$templateEngine->display( $templateName, null );
		}
	}
	
	protected function renderSubprojectsPanel() {
		//Поле доп. проектов
		$templateName = $this->_relatedTemplatePath.'block-projects.tpl';
		$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
		if ( !$templateEngine->isCached($templateName, null, -1, 0) ) {}
		$templateEngine->display( $templateName, null );
	}
	
	protected function renderTimerPanel() {
		//Таймер
		if ($this->_conf['menur_time'] == 1) {
			$templateName = $this->_relatedTemplatePath.'timer.tpl';
			$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
			if ( !$templateEngine->isCached($templateName, null, 2, 86400) ) {}
			$templateEngine->display( $templateName, null );
		}
	}
	
	protected function renderOthersPanel() {
		//Остальное
		$templateName = $this->_relatedTemplatePath.'other.tpl';
		$templateEngine = TemplateEngineAdapter::getInstanceBase($templateName);
		if ( !$templateEngine->isCached($templateName, null, 2, 86400) ) {}
		$templateEngine->display( $templateName, null );
	}
}