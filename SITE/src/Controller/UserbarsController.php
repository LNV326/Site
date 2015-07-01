<?php

namespace Controller;

use Controller\AbstractSiteController;

class UserbarsController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'UserbarsTemplate.php';
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
//Юзербар пользователя на форуме
		if ((!preg_match( "/^http:\/\//", $this->_sdk_info[userbar])) and (preg_match("/^upload:bar-(?:\d+)\.(?:\S+)/", $this->_sdk_info[userbar] ))) {
 			$userbar_img = preg_replace( "/^upload:/", "", $this->_sdk_info[userbar] );
 			$showForm = true;

			$dir = $this->_conf [site_path] . "files/userbars/";
// 			$url = "http://" . $this->_conf [site_url] . "/files/userbars/";
			$no_view = array (
					"..",
					".",
					"Thumbs.db",
					"thumbs.db" 
			);
			$files = array();
			if ($dh = opendir( $dir )) {
				while ( ! (($file = readdir( $dh )) === false) ) {
					if (is_file( "$dir/$file" ) and (! in_array( $file, $no_view )))
						$files[] = $file;
				}
				closedir( $dh );
			} else
				$error = true;
		}
		return array(
				'userbar_img' => $userbar_img,
				'showForm' => $showForm,
				'files' => $files,
				'error' => $error
		);
	}
}