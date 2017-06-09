<?php

namespace Routing;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
// use Symfony\Component\Config\Loader\Loader;

/**
 * Create a collection of Routes for page navigation
 * 
 * @author Nikolay Lukyanov
 * @version 1.0
 * @see http://symfony.com/doc/current/routing/custom_route_loader.html
 *
 */
class RouteLoader /*extends Loader*/ {
	
	private static $_loaded = false;
	
	private static $_map = array(
			'news_homepage' => array(
					'path' 			=> '/',
					'defaults' 		=> array('_controller' => 'Controller\NewsControllerDB')),
			/* === News === */
			'news_showPage' => array(
					'path' 			=> 'news={pageNum}',
					'defaults' 		=> array('_controller' => 'Controller\NewsControllerDB:showAction', 'pageNum' => 1),
					'requirements' 	=> array('pageNum' => '\d+')),
			'add_news'		=>	array(
					'path' 			=> 'page=add_news',
					'defaults' 		=> array('_controller' => 'Controller\AddNewsControllerDB')),
			/* === FAQ === */
			'faq_homepage' 	=> array(
					'path' 			=> 'page=faq',
					'defaults' 		=> array('_controller' => 'Controller\FAQControllerDB')),
			'faq_showCategory'	=> array(
					'path' 			=> 'page=faq&cat={categoryId}',
					'defaults' 		=> array('_controller' => 'Controller\FAQControllerDB'),
					'requirements' 	=> array('categoryId' => '\d+')),
			/* === Gallery === */
			'gallery_homepage' => array(
					'path' 			=> 'page=gallery',
					'defaults' 		=> array('_controller' => 'Controller\GalleryController')),
			'gallery_showCategory' => array(
					'path' 			=> 'page=gallery&cat={categoryId}',
					'defaults' 		=> array('_controller' => 'Controller\GalleryController'),
					'requirements' 	=> array('categoryId' => '\d+')),
			'gallery_showAlbum' => array(
					'path' 			=> 'page=gallery&subcat={albumId}',
					'defaults' 		=> array('_controller' => 'Controller\GalleryController'),
					'requirements' 	=> array('albumId' => '\d+')),
			/* === File Store === */
			'files_homepage'	=> array(
					'path' 			=> 'page=files',
					'defaults' 		=> array('_controller' => 'Controller\FilesControllerDB')),
			'files_showCategory'	=> array(
					'path' 			=> 'page=files&cat={categoryId}',
					'defaults' 		=> array('_controller' => 'Controller\FilesControllerDB'),
					'requirements' 	=> array('categoryId' => '\d+')),
			'files_showSubcategory'	=> array(
					'path' 			=> 'page=files&subcat={subcategoryId}',
					'defaults' 		=> array('_controller' => 'Controller\FilesControllerDB'),
					'requirements' 	=> array('subcategoryId' => '\d+')),
			'files_showFile' => array(
					'path' 			=> 'page=confirm_load&fid={fileId}',
					'defaults' 		=> array('_controller' => 'Controller\ConfirmLoadControllerDB'),
					'requirements' 	=> array('fileId' => '\d+')),
			/* === Others === */
			'about'				=> array(
					'path' 			=> 'page=about',
					'defaults' 		=> array('_controller' => 'Controller\AboutControllerDB')),
			'adver'				=> array(
					'path' 			=> 'page=adver',
					'defaults' 		=> array('_controller' => 'Controller\AdverControllerDB')),
			'chat'				=> array(
					'path' 			=> 'page=chat',
					'defaults' 		=> array('_controller' => 'Controller\ChatControllerDB')),
			'contact'			=> array(
					'path' 			=> 'page=contact',
					'defaults' 		=> array('_controller' => 'Controller\ContactControllerDB')),
			'gadgets'			=> array(
					'path' 			=> 'page=gadgets',
					'defaults' 		=> array('_controller' => 'Controller\GadgetsController')),
			'info'				=> array(
					'path' 			=> 'page=info',
					'defaults' 		=> array('_controller' => 'Controller\InfoControllerDB')),
			'links'				=> array(
					'path' 			=> 'page=links',
					'defaults' 		=> array('_controller' => 'Controller\LinksControllerDB')),
			'login'				=> array(
					'path' 			=> 'page=login',
					'defaults' 		=> array('_controller' => 'Controller\LoginControllerDB')),
			'search'			=> array(
					'path' 			=> 'page=search',
					'defaults' 		=> array('_controller' => 'Controller\SearchControllerDB')),
			'sms_money'			=> array(
					'path' 			=> 'page=sms_money',
					'defaults' 		=> array('_controller' => 'Controller\SMSMoneyControllerDB')),
			'stat'				=> array(
					'path' 			=> 'page=stat',
					'defaults' 		=> array('_controller' => 'Controller\StatisticControllerDB')),
			'uploads'			=> array(
					'path' 			=> 'page=uploads',
					'defaults' 		=> array('_controller' => 'Controller\UploadsController')),
			'userbars'			=> array(
					'path' 			=> 'page=userbars',
					'defaults' 		=> array('_controller' => 'Controller\UserbarsControllerDB')),
			/* === Others === */
			'articles_showPage'	=> array(
					'path' 			=> 'page={articleName}',
					'defaults' 		=> array('_controller' => 'Controller\ArticlesControllerDB:showArticleAction'),
					'requirements' 	=> array('articleName' => '\w+'))
	);
	
	public function load($resource, $type = null) {
		if (true === self::$_loaded)
			throw new \RuntimeException('Do not add the "extra" loader twice');
		$routes = new RouteCollection();
		
		try {
			// Prepare new route and add to routing collection
			foreach (self::$_map as $routeName => $routeInfo) {
				$route = new Route($routeInfo['path'], $routeInfo['defaults'], isset($routeInfo['requirements']) ? $routeInfo['requirements'] : array());
				$routes->add($routeName, $route);
			}
		} catch (\Exception $e) {
			echo 'Oh shit! Exception detected';
			throw new \RuntimeException('Can\'t load routes', 500, $e);
		}
	
		self::$_loaded = true;	
		return $routes;
	}
	
	public function supports($resource, $type = null) {
		return 'extra' === $type;
	}
}