<?php

namespace Controller;

use Controller\AbstractSiteController;

class SearchControllerDB extends AbstractSiteController {
	
	protected function getData() {
		//Подгрузка необходимостей )
		include "admin/ad_editor/functions/global.php";
		//Определение страницы
		$num=15;
		$p=intval($this->_nfs->input['p']);
		$start=$p*$num;
		//Определения ID сессии
		if (($this->_nfs->input[searchid]=='') and ($this->_nfs->input['keywords']=='')) {
			echo "<form action='index.php?page=search&search_in=topics&start=1' method='POST' style='font-size:8pt;text-align:justify;margin:0cm'>";
			echo "<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>\n";
			echo "<tr bgcolor='#424242'><td colspan=2><p align=center>Поиск по всему сайту</p></td></tr>\n";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#424242'>";
			echo "<td colspan=2><p class=normal>Здесь вы сможете найти интерисующую вас информация по ключевому слову. Поиск производится в названиях тем, теле сообщений форума а так-же в теле и названиях страниц самого сайта. Результаты выводятся отдельно:</p></td>\n";
			echo "</tr>";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#373737'>";
			echo "<td width=65%><p>Ключевое слово(а) для поиска (не менее 4 символов):</p></td>\n";
			echo "<td width=35%><input type='text' name='keywords' class=textinput size='47' value=''></td>\n";
			echo "</tr>";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#373737'>";
			echo "<td colspan=2><p align=center><input type='submit' value='Поиск по сайту' wight='15' class=forminput></p></td>\n";
			echo "</tr>";
			echo "<tr bgcolor='#212121'><td colspan=2><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
			echo "<tr bgcolor='#424242'><td colspan=2><p align=center>© ".$this->_conf[site_name]." ".$this->_conf[site_start_year]." - ".date(Y).$pages."</p></td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
			$error=TRUE;
		} else if ($this->_nfs->input[searchid]=='') {
			$keywords = trim($SDK->filter_keywords($this->_nfs->input['keywords']));
			if (strlen($keywords)<4) {
				get_rezult('Ключевое слово для поиска должно состоять минимум из 4 символов!');
				get_links();
				$error=TRUE;
			} else {
				$searchid = $SDK->simple_search($keywords);
			}
		} else {
			$searchid = $this->_nfs->input[searchid];
		}
		//Если нет ошибки
		if (!$error) {
			//Получение результатов из таблицы
			$this->_DB->query("SELECT * FROM ibf_search_results WHERE id='".$searchid."'");
			$row = $this->_DB->fetch_row();
			$line_search[topics] = $row['topic_id'];
			$line_search[posts]  = $row['post_id'];
		
			//Модули \ страницы
			foreach( explode( ",", $row['page_id']) as $tid )
			{
				if (preg_match( "/^m_/", $tid ) and ($tid <> "")){
					$tid=intval(str_replace( "m_", "", $tid ) );
					$line_search[modules] .= ",$tid,";
				} else {
					$line_search[pages] .= "$tid,";
				}
			}
			$line_search[pages]  = str_replace( ",,", ",", $line_search[pages] );
			$line_search[modules]  = str_replace( ",,", ",", $line_search[modules] );
		
			$hits_search[topics] = $row['topic_max'];
			$hits_search[posts]  = $row['post_max'];
			$hits_search[pages]  = $row['page_max'];
			//Где искать?
			if ($this->_nfs->input['search_in']=="") {
				$search_in="topics";
			} else {
				$search_in=$this->_nfs->input['search_in'];
			}
			//Проверка есть ли там что-то
			if ($this->_nfs->input[start]==1) {
				if ($search_in=="topics") {
					if ($hits_search[topics]==0) {
						$search_in="posts";
						if ($hits_search[posts]==0) {
							$search_in="pages";
						}
					}
				}
			}
			//Выборка результатов
			$themes = $SDK->get_search_results($searchid,$search_in,$line_search,$start,$num);
			//Формирование списка страниц
			$pages=pages("index.php?page=search&searchid=".$searchid."&search_in=".$search_in,$hits_search[$search_in],$num);
			//Вывод результатов!
			if (($hits_search[topics]<>0) or ($hits_search[posts]<>0) or ($hits_search[pages]<>0)) {
				if ($search_in=="topics") {
					$count=0;
					echo "<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>\n";
					echo "<tr bgcolor='#424242'><td colspan=4><p class=news align=center>Результаты поиска в темах форума".$pages."</p></td></tr>\n";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					echo "<tr bgcolor='#373737'>";
					echo "<td colspan=4><p class=news>Результаты поиска по сайту в целом:<br>
				Найдено ".$hits_search[topics]." совпадений в названиях тем форума. (<a href='index.php?page=search&searchid=".$searchid."&search_in=topics'>показать</a>)<br>
				Найдено ".$hits_search[posts]." совпадений в сообщениях форума. (<a href='index.php?page=search&searchid=".$searchid."&search_in=posts'>показать</a>)<br>
				Найдено ".$hits_search[pages]." совпадений в страницах сайта. (<a href='index.php?page=search&searchid=".$searchid."&search_in=pages'>показать</a>)</p></td>\n";
					echo "</tr>";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					echo "<tr bgcolor='#424242'>";
					echo "<td width='50%'><p class=news>Тема / Автор / Форум:</p></td>\n";
					echo "<td width='10%'><p class=news align=center>Ответов</p></td>\n";
					echo "<td width='15%'><p class=news align=center>Просмотров</p></td>\n";
					echo "<td width='25%'><p class=news align=center>Последний ответ</p></td>\n";
					echo "</tr>";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					for ($i=0;$i<=count($themes)-1;$i++) {
						echo "<tr bgcolor='#373737'>";
						echo "<td><p class=news>Тема: <a href='/forum/index.php?showtopic=".$themes[$i][tid]."' target='_blank'>".$themes[$i][title]."</a><br>Автор: <a href='/forum/index.php?showtopic=".$themes[$i][starter_id]."' target='_blank'>".$themes[$i][starter_name]."</a><br>Форум: <a href='/forum/index.php?showforum=".$themes[$i][forum_id]."' target='_blank'>".$themes[$i][forum_name]."</a></p></td>\n";
						echo "<td><p class=news align=center>".$themes[$i][posts]."</p></td>\n";
						echo "<td><p class=news align=center>".$themes[$i][views]."</p></td>\n";
						echo "<td><p class=news align=center>Автор: <a href='/forum/index.php?showuser=".$themes[$i][last_poster_id]."' target='_blank'>".$themes[$i][last_poster_name]."</a><br>".$themes[$i][last_post]."</p></td>\n";
						echo "</tr>";
						$count++;
					}
					if ($count==0) {
						echo "<tr bgcolor='#373737'>";
						echo "<td colspan=4><p class=news align=center>Ничего не найдено</p></td>\n";
						echo "</tr>";
					}
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					echo "<tr bgcolor='#424242'><td colspan=4><p class=news align=center>© ".$this->_conf[site_name]." ".$this->_conf[site_start_year]." - ".date(Y).$pages."</p></td></tr>\n";
					echo "</table>\n";
				} else if ($search_in=="posts") {
					$count=0;
					echo "<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>\n";
					echo "<tr bgcolor='#424242'><td colspan=4><p class=news align=center>Результаты поиска в сообщениях форума".$pages."</p></td></tr>\n";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					echo "<tr bgcolor='#373737'>";
					echo "<td colspan=4><p class=news>Результаты поиска по сайту в целом:<br>
				Найдено ".$hits_search[topics]." совпадений в названиях тем форума. (<a href='index.php?page=search&searchid=".$searchid."&search_in=topics'>показать</a>)<br>
				Найдено ".$hits_search[posts]." совпадений в сообщениях форума. (<a href='index.php?page=search&searchid=".$searchid."&search_in=posts'>показать</a>)<br>
				Найдено ".$hits_search[pages]." совпадений в страницах сайта. (<a href='index.php?page=search&searchid=".$searchid."&search_in=pages'>показать</a>)</p></td>\n";
					echo "</tr>";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					for ($i=0;$i<=count($themes)-1;$i++) {
						echo "<tr bgcolor='#373737'>";
						echo "<td width=70%><p class=news>Автор: <a href='/forum/index.php?showuser=".$themes[$i][starter_id]."' target='_blank'>".$themes[$i][starter_name]."</a></p></td>\n";
						echo "<td width=30%><p class=news align=right>Дата: ".$themes[$i][post_date]."</p></td>\n";
						echo "</tr>";
						echo "<tr bgcolor='#373737'><td colspan=2><p class=news>Тема: <a href='/forum/index.php?showtopic=".$themes[$i][tid]."' target='_blank'><b>".$themes[$i][title]."</b></a></p><p class=news>".$themes[$i][post]."</p></td></tr>";
						echo "<tr bgcolor='#373737'>";
						echo "<td><p class=news>Форум: <a href='/forum/index.php?showforum=".$themes[$i][fid]."' target='_blank'>".$themes[$i][forum_name]."</a></p></td>\n";
						echo "<td><p class=news align=right><a href='/forum/index.php?showtopic=".$themes[$i][tid]."&view=findpost&p=".$themes[$i][pid]."' target='_blank'>Показать сообщение</a></p></td>\n";
						echo "</tr>";
						echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
						$count++;
					}
					if ($count==0) {
						echo "<tr bgcolor='#373737'>";
						echo "<td colspan=4><p class=news align=center>Ничего не найдено</p></td>\n";
						echo "</tr>";
						echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					}
					echo "<tr bgcolor='#424242'><td colspan=4><p class=news align=center>© ".$this->_conf[site_name]." ".$this->_conf[site_start_year]." - ".date(Y).$pages."</p></td></tr>\n";
					echo "</table>\n";
				} else if ($search_in=="pages") {
					$count=0;
					echo "<table align=center width=98% cellspacing=1 cellpadding=1 style='border-top:1px solid #555555; border-bottom:1px solid #555555; border-left:1px solid #555555; border-right:1px solid #555555;margin:3pt'>\n";
					echo "<tr bgcolor='#424242'><td colspan=4><p class=news align=center>Результаты поиска в страницах сайта</p></td></tr>\n";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					echo "<tr bgcolor='#373737'>";
					echo "<td colspan=4><p class=news>Результаты поиска по сайту в целом:<br>
				Найдено ".$hits_search[topics]." совпадений в названиях тем форума. (<a href='index.php?page=search&searchid=".$searchid."&search_in=topics'>показать</a>)<br>
				Найдено ".$hits_search[posts]." совпадений в сообщениях форума. (<a href='index.php?page=search&searchid=".$searchid."&search_in=posts'>показать</a>)<br>
				Найдено ".$hits_search[pages]." совпадений в страницах сайта. (<a href='index.php?page=search&searchid=".$searchid."&search_in=pages'>показать</a>)</p></td>\n";
					echo "</tr>";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					echo "<tr bgcolor='#424242'>";
					echo "<td width='85%'><p class=news>Название страницы сайта</p></td>\n";
					echo "<td width='15%'><p class=news align=center>Просмотров</p></td>\n";
					echo "</tr>";
					echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					for ($i=0;$i<=count($themes)-1;$i++) {
						echo "<tr bgcolor='#373737'>";
						echo "<td width=80%><p class=news><a href='index.php?page=".$themes[$i][name]."' target='_blank'>".$themes[$i][title]."</a></p></td>\n";
						echo "<td width=20%><p class=news align=center>".$themes[$i][count]."</p></td>\n";
						echo "</tr>";
						$count++;
					}
					if ($count==0) {
						echo "<tr bgcolor='#373737'>";
						echo "<td colspan=4><p class=news align=center>Ничего не найдено</p></td>\n";
						echo "</tr>";
						echo "<tr bgcolor='#212121'><td colspan=4><p style='font-size:3pt'>&nbsp;</p></td></tr>\n";
					}
					echo "<tr bgcolor='#424242'><td colspan=4><p class=news align=center>© ".$this->_conf[site_name]." ".$this->_conf[site_start_year]." - ".date(Y)."</p></td></tr>\n";
					echo "</table>\n";
				} else {
					get_rezult('Ничего не найдено!');
					get_links();
				}
			} else {
				get_rezult('Ничего не найдено!');
				get_links();
			}
		}
	}
}