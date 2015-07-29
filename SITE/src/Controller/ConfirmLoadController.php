<?php

namespace Controller;

use Controller\AbstractSiteController;

class ConfirmLoadController extends AbstractSiteController {
	
	protected $_templateEngine = 'purePHP';
	protected $_templateName = 'ConfirmLoadTemplate.php';
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		$fid=intval($this->_nfs->input['fid']);
		if ($fid<=0) $fid=intval($_GET['go']);
		if ($fid<=0) page404(); // TODO Need to create 404 more beautiful =)
		$this->_DB->query("SELECT * FROM s_files_db WHERE id=".$fid.";");
		$row=$this->_DB->fetch_row();		
		if ($row[link]==0) {
			$filename=file_name($row[name]); //Имя файла
			$size = 0;
			$filesize=get_size($row[url],$size);
			$filelink="http://".$this->_conf[site_url]."/download.php?go=".$fid."&check=1&link=1";
		} else {
			$filename=$row[name];
			$filelink=$row[url]; //Ссылка до файла
		}
						
		
		return array(
				'file' => $row,
				'fid' => $fid,
				'filelink' => $filelink,
				'filename' => $filename,
				'filesize' => $filesize
		);
	}

}