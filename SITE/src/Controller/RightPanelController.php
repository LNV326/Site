<?php

namespace Controller;

use Service\GalleryService;
use Service\FilesStoreService;
/**
 * Refactoring from old site engine version (year 2003).
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0
 *
 */
class RightPanelController extends AbstractComplexController  {
	
	protected $_templateEngine = 'purePHP';
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
		if (($this->_conf['menur_login']==1) and (!$this->_SDK->is_loggedin())){
			$this->_smarty->cache_lifetime = -1;
			$this->_smarty->display($this->_relatedTemplatePath.'block-login.tpl');
		}
	}

	protected function renderWhoIsOnlinePanel() {
		//Кто онлайн
		if ($this->_conf['menur_online'] == 1){
			$this->_smarty->caching = 2;
			$this->_smarty->cache_lifetime = 60; // На 60 секунд
			if (! $this->_smarty->is_cached( 'right_menu/online.tpl' )) {
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
				$this->_smarty->assign( 'mem_count', $row ['count'] );
				$this->_smarty->assign( 'mem_str', $to_echo );
				$this->_DB->query( "SELECT count(member_id) as count FROM ibf_sessions WHERE member_id = 0 AND running_time > " . (time() - 900) . " LIMIT 0,1" );
				$row = $this->_DB->fetch_row();
				$this->_smarty->assign( 'que_count', $row ['count'] ); 
			}
			$this->_smarty->display($this->_relatedTemplatePath.'block-online.tpl');
		}
	}
	
	protected function renderModeratorPanel() {
		//Панель модератора
		if (($this->_sdk_info[id]==$this->_admins['root']) or ($this->_SDK->is_small_siteadmin($this->_sdk_info['id']) == TRUE) or ($this->_SDK->is_full_siteadmin($this->_sdk_info['id']) == TRUE)) {
			$this->_smarty->caching = 2;
			$this->_smarty->cache_lifetime = 10;  //Раз в минуту
			if (!$this->_smarty->is_cached('right_menu/mod_cp.tpl')) {
				GalleryService::init($this->_conf, $this->_DB);
				$this->_smarty->assign('images_count', GalleryService::getCountForReview());
				FilesStoreService::init($this->_conf, $this->_DB);
				$this->_smarty->assign('files_count', FilesStoreService::getCountForReview());
			}
			$this->_smarty->display($this->_relatedTemplatePath.'block-modcp.tpl');
		}
	}
		
	protected function renderRandomScreenshot() {
		//Случайный скриншот
		if ($this->_conf['menur_gallery'] == 1){
			$vid = rand(1,5);
			$this->_smarty->caching = 2;
			$this->_smarty->cache_lifetime = 60;  //На 60 секунд
			if (!$this->_smarty->is_cached('right_menu/rnd_screen.tpl', $vid)) {
				GalleryService::init($this->_conf, $this->_DB);
				$imageInfo = GalleryService::getRandomImage();
				$this->_smarty->assign('image', $imageInfo['image']);
				$this->_smarty->assign('subcat_row', $imageInfo['subcat']);
				$this->_smarty->assign('size_px', $imageInfo['size_px']);
			}
			$this->_smarty->display($this->_relatedTemplatePath.'block-rndscreen.tpl',$vid);
		}
	}
	
	protected function renderSearchPanel() {
		//Поле поиска
		if ($this->_conf['menur_search'] == 1){
			$this->_smarty->cache_lifetime = -1;
			$this->_smarty->display($this->_relatedTemplatePath.'block-search.tpl');
		}
	}
	
	protected function renderSubprojectsPanel() {
		//Поле доп. проектов
		$this->_smarty->cache_lifetime = -1;
		$this->_smarty->display($this->_relatedTemplatePath.'block-projects.tpl');
	}
	
	protected function renderTimerPanel() {
		//Таймер
		if ($this->_conf['menur_time'] == 1){
			$this->_smarty->caching = 2;
			$this->_smarty->cache_lifetime = 86400;  //Раз в сутки
			$this->_smarty->display($this->_relatedTemplatePath.'timer.tpl');
		}
	}
	
	protected function renderOthersPanel() {
		//Остальное
		$this->_smarty->caching = 2;
		$this->_smarty->cache_lifetime = 86400;  //Раз в сутки
		$this->_smarty->display($this->_relatedTemplatePath.'other.tpl');
	}

	protected function postIndexHook() {
		//Удаляем используемые в модуле Smarty-переменные
		$this->_smarty->clear_assign(array('mem_count','que_count','mem_str','img_count','image','subcat_row','size_px'));
	}

}