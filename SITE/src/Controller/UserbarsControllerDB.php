<?php

namespace Controller;

use Controller\AbstractSiteController;

/**
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0 Tested 08/08/2015
 *
 * Refactoring from old site engine version (year 2003). All HTML code transfered to template file.
 *
 */
class UserbarsControllerDB extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'UserbarsTemplate.php';
	
	protected function getData() {
//Юзербар пользователя на форуме
		if ((!preg_match( "/^http:\/\//", $this->_sdk_info[userbar])) and (preg_match("/^upload:bar-(?:\d+)\.(?:\S+)/", $this->_sdk_info[userbar] ))) 
 			$userbar_img = preg_replace( "/^upload:/", "", $this->_sdk_info[userbar] );

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
		return array(
				'userbar_img' => $userbar_img,
				'files' => $files,
				'error' => $error
		);
	}
}