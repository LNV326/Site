<?php

namespace Service;

class FilesStoreService {
	
	private static $_conf;
	private static $_DB;
	
	public function __construct() {}
	
	public static function init($conf, $DB) {
		self::$_conf = $conf;
		self::$_DB = $DB;
	}
	
	public static function getCountForReview() {
		self::$_DB->query("SELECT count(id) as count FROM s_files_db WHERE `show`='N';");
        $files_count = self::$_DB->fetch_row();
        return $files_count['count'];        
	}
}