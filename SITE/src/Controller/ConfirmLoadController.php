<?php

namespace Controller;

use Controller\AbstractSiteController;

class ConfirmLoadController extends AbstractSiteController {
	
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		$fid=intval($this->_nfs->input['fid']);
		if ($fid<=0) $fid=intval($_GET['go']);
		if ($fid<=0) page404();
		$this->_DB->query("SELECT * FROM s_files_db WHERE id=".$fid.";");
		$row=$this->_DB->fetch_row();
		//Имя файла
		if ($row[link]==0) {
			$filename=file_name($row[name]);
		} else {
			$filename=$row[name];
		}
		//Размер
		if ($row[link]==0) {
			$filesize=get_size($row[url],$size);
		}
		//Ссылка до файла
		if ($row[link]<>1) {
			$filelink="http://".$this->_conf[site_url]."/download.php?go=".$fid."&check=1&link=1";
		} else {
			$filelink=$row[url];
		}
		
		echo "<script language='JavaScript' type='text/javascript'>\n";
		echo "function view_url()\n";
		echo "{\n";
		echo " prompt ('Ссылка к файлу ".$filename." (".$filesize."):','".$filelink."')\n";
		echo "}\n";
		echo "</script>\n";
		
		echo "<form action='download.php?go=".$fid."' method='post' name='Confirm' style='margin:3pt'>\n";
		echo "<input type='hidden' name='check' value='1'>\n";
		echo "<table align=center class=table border=0 cellspacing=0 style='width:98%'>\n";
		echo "<tr style='height:15pt'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal><b>".$filename."</b></p></td></tr>\n";
		echo "<tr style='height:15pt'>\n";
		
		if ($row[img]<>'') {
			echo "<td style='border:solid windowtext 1.0pt'><p class=normal>Описание:<br>".$this->_nfs->unconvert_html($row[description])."<br>";
			if ($row[autor]<>'') {
				echo "<br>Авторы: <b>".$row[autor]."</b>";
			}
			if ($row[link]==0) {
				echo "<br>Размер файла: ".$filesize;
			}
			echo "<br>Скачиваний: <b>".$row[count]."</b>.</p></td>\n";
			//Проверка рисунка
			if ($row[link]<>'1') {
				if (($row[img]=='') or (!file_exists($this->_conf[images_path].$row[img]))) {
					$row[img]='/style/'.$this->_style_id.'/img/no_image.gif';
				}
				if ($row[img_big]<>'') {
					$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$row[img_big]."' target='_blank'>Увеличить</a></p>";
				} else {
					$linktobig="";
				}
			} else {
				$linktobig="<p align=center><a href='http://images.".$this->_conf[site_url]."/".$row[img_big]."' target='_blank'>Увеличить</a></p>";
			}
			echo "<td style='border:solid windowtext 1.0pt'><p align=center><img src='http://images.".$this->_conf[site_url]."/".$row[img]."' border=0></p>".$linktobig."</td>\n";
		} else {
			echo "<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Описание:<br>".$this->_nfs->unconvert_html($row[description])."<br>";
			if ($row[autor]<>'') {
				echo "<br>Авторы: <b>".$row[autor]."</b>";
			}
			if ($row[link]==0) {
				echo "<br>Размер файла: ".$filesize;
			}
			echo "<br>Скачиваний: <b>".$row[count]."</b>.</p></td>\n";
		}
		echo "</tr>\n";
		echo "<tr style='height:1pt;background:#151515'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		echo "<tr style='height:15pt'>\n";
		if ($row[link]==0) {
			echo "<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Если при скачивании файла у вас возникли какие либо проблемы обратитесь к администрации.</p><p class=normal>Если вы хотите скачать файл менеджером закачки файлов, то для получения ссылки на файл воспользуйтесь кнопкой (Ссылка). Докачка поддерживается!</p></td>\n";
		} else {
			echo "<td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal>Этот файл находится не на нашем сервере. Нажав кнопку вы попадёте либо на страницу с которой можно будет загрузить этот файл, либо на сам файл. Если ссылка не работает сообщите об этом администрации!</p><p class=normal>Для получения ссылки на файл или страницу, нажмите на кнопку (Показать ссылку).</p></td>\n";
		}
		echo "</tr>\n";
		echo "<tr style='height:1pt;background:#151515'><td colspan=2 style='border:solid windowtext 1.0pt'><p class=normal style='font-size:1pt'>&nbsp;</p></td></tr>\n";
		echo "<tr style='height:15pt'>\n";
		if ($row[link]==0) {
			//
			echo "<td style='width:75%;border:solid windowtext 1.0pt'><p class=normal><input type='submit' name='submit' class='forminput' value='Скачать / Download'>  <input type='button' name='viewurl' class='forminput' value='Ссылка / Link' onclick='view_url()'><p class=normal></p></td>\n";
		} else {
			echo "<td style='width:75%;border:solid windowtext 1.0pt'><p class=normal><input type='submit' name='submit' class='forminput' value='Перейти по ссылке / Download'> <input type='button' name='viewurl' class='forminput' value='Показать ссылку / Show link' onclick='view_url()'></p></td>\n";
		}
		echo "<td style='width:25%;border:solid windowtext 1.0pt'><p align=center><input name='Button' class=forminput type='button' value='Вернуться назад' onClick='javascript:history.go(-1)'></p></td></tr>\n";
		echo "</table></form>\n";
	}

}