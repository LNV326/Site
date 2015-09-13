<?php

namespace Service;

/**
 * Contains all functions to work with Gallery (including categories, albums and images)
 *
 * @author Nikolay Lukyanov
 *
 * @version 1.0 
 *
 */
class GalleryService {
	
	private static $_conf;
	private static $_DB;
	
	public function __construct() {}
	
	public static function init($conf, $DB) {
		self::$_conf = $conf;
		self::$_DB = $DB;
	}
	
	public static function getRandomImage() {
		if (!empty(self::$_conf['gallery_dir']) and self::$_conf['gallery_dir']<>'0') {
            $sql_add="subcat IN (".self::$_conf['gallery_dir'].") and ";
        } else $sql_add="";
        self::$_DB->query("SELECT * FROM s_gallery_images WHERE ".$sql_add."allow_add=1 ORDER BY rand() limit 1");
        $image = self::$_DB->fetch_row();
        //Получение данный о подкатегории
        self::$_DB->query("SELECT * FROM s_gallery_subcat WHERE id='".$image[subcat]."';");
        $subcat_row = self::$_DB->fetch_row();
        //Размер изображения
        $size_px = getimagesize(self::$_conf[images_path]."gallery/".$subcat_row[dir_name]."/thumbs/".$image[filename]);
        if (!$size_px[0]) {
            $size_px[0]=0;
            $size_px[1]=0;
        }
        return array(
        		'image'	=> $image,
        		'subcat' => $subcat_row,
        		'size_px' => $size_px
        );
	}
	
	public static function getCountForReview() {
		self::$_DB->query("SELECT count(id) as count FROM s_gallery_images WHERE allow_add='0';");
		$img_count = self::$_DB->fetch_row();
		return $img_count['count'];
	}
}