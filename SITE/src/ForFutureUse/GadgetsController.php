<?php

namespace Controller;

use Controller\AbstractSiteController;

class GadgetsController extends AbstractSiteController {
	
	protected $_caching = 0;
	
	var $module_name = "Gadgets";
	var $module_id = 1;
	var $result = null;
	var $err_mess = array();// Сообщения об ошибках
	var $col_count = 2;		// Стандартное количество колонок
	var $col_max = 3;		// Максимальное колечество колонок
	var $bdrop = array();	// Рабочие настройки вывода блоков
	var $attr = null;
	// Стандартные настройки вывода блоков
	var $default = array(
			array('lastfiles','lastposts','notepad','twitter'),
			array('lastcomments','lastnews')
	);
	// Стандартные настройки каждого блока
	var $settings = array(
			'lastposts' 	=> array('name'=>'Последние активные темы',		'set' => array('who','when'), 'visible' => true,	'count' => 7,	'cache'=>600),
			'lastfiles' 	=> array('name'=>'Новые файлы в архиве',		'set' => array('who','when'), 'visible' => true,	'count' => 7,	'cache'=>600),
			'lastcomments' 	=> array('name'=>'Последние комментарии',		'set' => array('who','when'), 'visible' => true,	'count' => 7,	'cache'=>600),
			'lastnews' 		=> array('name'=>'Последние новости',			'set' => array('when'),		  'visible' => true,	'count' => 7,	'cache'=>600),
			'yplayer' 		=> array('name'=>'Youtube Player',											  'visible' => true),
			'vkontakte'		=> array('name'=>'Панелька ВКонтакте',										  'visible' => true),
			'notepad'		=> array('name'=>'Персональный блокнот',									  'visible' => true),
			'twitter'		=> array('name'=>'Панель Twitter',											  'visible' => true)
	);
	
	private function ShowGadgets($func_name='', $attr=null) {
		if (isset($_COOKIE['blocks'])) {
			// Определяем настройки
			parse_str($_COOKIE['blocks'], $set);
			// Пробег по настройкам с целью декодирования и удаления пустых ячеек в роде: 0[1] => null
			foreach ($set as $cid => $col) {
				$this->bdrop[] = array();
				foreach ($col as $eid => $elm) {
					$elm = explode(' ',$elm);
					if (!is_null($elm[1])) {
						switch ($elm[1]) {
							case 0:
							case 4: $this->settings[$elm[0]]['set'] = array(); break;
							case 1:
							case 5: $this->settings[$elm[0]]['set'] = array('who'); break;
							case 2:
							case 6: $this->settings[$elm[0]]['set'] = array('when'); break;
							case 3:
							case 7: $this->settings[$elm[0]]['set'] = array('who','when'); break;
						}
						if ($elm[1] >3) $this->settings[$elm[0]]['visible'] = false;
					}
					if (!is_null($elm[2]))
						if ($elm[2] <= 30) $this->settings[$elm[0]]['count'] = $elm[2];
					else $this->settings[$elm[0]]['count'] = 30;
					$this->bdrop[$cid][] = $elm[0];
				}
			}
				
		} else $this->bdrop = $this->default;
		// Если надо получить всю страницу, а не конкретный блок
		if ($func_name == '') {
			// Получаем содержимое блоков
			$this->getBlocks();
			// Узнаём ширину колонок
			// Если ширина пользователем не задана, делаем поровну
			$width = array();
			$w = 100/$this->col_count;
			for ($i=0; $i<$this->col_count; $i++) $width[] = $w;
			$this->_smarty->caching = false;  //Никогда не кэшировать
			$this->_smarty->assign('bdrop', $this->bdrop);
			$this->_smarty->assign('set', $this->settings);
			$this->_smarty->assign('width', $width);
			$this->_smarty->assign('count', $this->col_count);
			$this->_smarty->assign('lgdin', $this->_sdk_info[id]);
			$this->result = $this->_smarty->fetch("modules/gadgets/gadgets.tpl");
			$this->_smarty->clear_assign(array('bdrop','set','block_content','width','count'));
		} else {
			// Загрузка конкретного блока c параметрами
			if (!is_null($attr)) $this->attr = $attr;
			if (method_exists($this, $func_name)) {
				$this->result = $this->$func_name();
				$this->result = $this->result['content'];
			} else {
				$this->err_mess[] = 'Отсутствует метод '.$func_name.' в модуле '.$this->module_name;
			}
			$this->_smarty->clear_assign(array('block_content'));
		}
		$this->err_mess = implode("<br/>", $this->err_mess);
	}
	
	//=========================================
	// Оформление данных
	//=========================================
	
	// Формирование даты/времени
	protected function datetime($dt) {
		if (empty($dt)) return '';
		if (is_string($dt))	$dt = strtotime($dt);
		//Сегодня, вчера, завтра
		if(date('Y') == date('Y',$date)) {
			if(date('z') == date('z', $date)) {
				$result_date = date('Сегодня'.$time, $date);
			} elseif(date('z') == date('z',mktime(0,0,0,date('n',$date),date('j',$date)+1,date('Y',$date))) /*z+1*/) {
				$result_date = date('Вчера'.$time, $date);
			}
			if(isset($result_date)) return $result_date;
		}
		return $dt;
	}
	
	// Последние сообщения (отображение)
	protected function lastposts() {
		$this->_smarty->caching = false;  //Никогда
		if ($this->_smarty->template_exists('modules/gadgets/last_posts.tpl')) {
			if (!$this->_smarty->is_cached('modules/gadgets/last_posts.tpl')) {
				$this->_smarty->assign('block_content', $this->lastposts_data());
				$this->_smarty->assign('set', $this->settings[__FUNCTION__]['set']);
			}
			return array('id' => __FUNCTION__,
					'content' => $this->_smarty->fetch("modules/gadgets/last_posts.tpl")
			);
		} else {
			$this->err_mess[] = 'Отсутствует шаблон last_posts.tpl в модуле '.$this->module_name;
			return array('id' => __FUNCTION__,
					'content' => 'Отсутствует шаблон last_posts.tpl в модуле '.$this->module_name
			);
		}
	}
	
	// Последние файлы в архиве (отображение)
	protected function lastfiles() {
		$this->_smarty->caching = false;  //Никогда
		if ($this->_smarty->template_exists('modules/gadgets/last_files.tpl')) {
			if (!$this->_smarty->is_cached('modules/gadgets/last_files.tpl')) {
				$this->_smarty->assign('block_content', $this->lastfiles_data());
				$this->_smarty->assign('set', $this->settings[__FUNCTION__]['set']);
			}
			return array('id' => __FUNCTION__,
					'content' => $this->_smarty->fetch("modules/gadgets/last_files.tpl")
			);
		} else {
			$this->err_mess[] = 'Отсутствует шаблон last_files.tpl в модуле '.$this->module_name;
			return array('id' => __FUNCTION__,
					'content' => 'Отсутствует шаблон last_files.tpl в модуле '.$this->module_name
			);
		}
	}
	
	// Последние комментарии (отображение)
	protected function lastcomments() {
		$this->_smarty->caching = false;  //Никогда
		if ($this->_smarty->template_exists('modules/gadgets/last_comments.tpl')) {
			if (!$this->_smarty->is_cached('modules/gadgets/last_comments.tpl')) {
				$this->_smarty->assign('block_content', $this->lastcomments_data());
				$this->_smarty->assign('set', $this->settings[__FUNCTION__]['set']);
			}
			return array('id' => __FUNCTION__,
					'content' => $this->_smarty->fetch("modules/gadgets/last_comments.tpl")
			);
		} else {
			$this->err_mess[] = 'Отсутствует шаблон last_comments.tpl в модуле '.$this->module_name;
			return array('id' => __FUNCTION__,
					'content' => 'Отсутствует шаблон last_comments.tpl в модуле '.$this->module_name
			);
		}
	}
	
	// Последние сообщения (отображение)
	protected function lastnews() {
		$this->_smarty->caching = false;  //Никогда
		if ($this->_smarty->template_exists('modules/gadgets/last_news.tpl')) {
			if (!$this->_smarty->is_cached('modules/gadgets/last_news.tpl')) {
				$this->_smarty->assign('block_content', $this->lastnews_data());
				$this->_smarty->assign('set', $this->settings[__FUNCTION__]['set']);
			}
			return array('id' => __FUNCTION__,
					'content' => $this->_smarty->fetch("modules/gadgets/last_news.tpl")
			);
		} else {
			$this->err_mess[] = 'Отсутствует шаблон last_news.tpl в модуле '.$this->module_name;
			return array('id' => __FUNCTION__,
					'content' => 'Отсутствует шаблон last_news.tpl в модуле '.$this->module_name
			);
		}
	}
	
	// Блокнот (отображение) --- Этот блок в принципе не кэшировать, потому что он уникален для каждого юзверя
	protected function notepad() {
		$this->_smarty->caching = false;  //Никогда
		if ($this->_smarty->template_exists('modules/gadgets/notepad.tpl')) {
			if ($this->attr) {
				$this->attr = $this->_nfs->clean_value($this->attr);
				$this->notepad_save();
			} else {
				if (!$this->_smarty->is_cached('modules/gadgets/notepad.tpl')) {
					$this->_smarty->assign('block_content', $this->notepad_data());
					$this->_smarty->assign('set', $this->settings[__FUNCTION__]['set']);
				}
				return array('id' => __FUNCTION__,
						'content' => $this->_smarty->fetch("modules/gadgets/notepad.tpl")
				);
			}
		} else {
			$this->err_mess[] = 'Отсутствует шаблон notepad.tpl в модуле '.$this->module_name;
			return array('id' => __FUNCTION__,
					'content' => 'Отсутствует шаблон notepad.tpl в модуле '.$this->module_name
			);
		}
	}
	
	// Плеер Youtube (отображение)
	protected function yplayer() {
		$p = array();
		$p[] = '<object width="480" height="385"><param name="movie" value="http://www.youtube.com/p/FC1890D19082F6EC?hl=ru_RU&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/p/FC1890D19082F6EC?hl=ru_RU&fs=1" type="application/x-shockwave-flash" width="480" height="385" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
		$p[] = '<object width="480" height="385"><param name="movie" value="http://www.youtube.com/p/2251ABF21E349394?hl=ru_RU&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/p/2251ABF21E349394?hl=ru_RU&fs=1" type="application/x-shockwave-flash" width="480" height="385" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
		$p[] = '<object width="480" height="385"><param name="movie" value="http://www.youtube.com/p/735C66C21DCC1838?hl=ru_RU&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/p/735C66C21DCC1838?hl=ru_RU&fs=1" type="application/x-shockwave-flash" width="480" height="385" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
	
		//$pl='<div style="width:100%; text-align:center;"><object height="385" width="100%"><param value="http://www.youtube.com/cp/vjVQa1PpcFMSJKY857ty9iq6waS7Pk5ScmYCg2bWqFM=" name="movie"><param value="true" name="allowFullScreen"><param value="always" name="allowscriptaccess"><embed height="385" width="100%" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" src="http://www.youtube.com/cp/vjVQa1PpcFMSJKY857ty9iq6waS7Pk5ScmYCg2bWqFM="></object></div>';
	
		return array('id' => __FUNCTION__,
		'content' => '<div style="width:100%; text-align:center;">'.$p[rand(0,2)].'</div>'
				);
	}
	
	// Связь с ВКонтакте
	protected function vkontakte() {
		return array('id' => __FUNCTION__,
				'content' => '<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?22"></script><div style="width:100%; text-align:center;"><!-- VK Widget --><div id="vk_groups"></div><script type="text/javascript">VK.Widgets.Group("vk_groups", {mode: 0, height: "290", margin: "0 auto"}, 456662);</script><p>Вообще-то я хочу выводить здесь новости группы через API</p></div>'
		);
	}
	
	// Связь с Twitter
	protected function twitter() {
		return array('id' => __FUNCTION__,
				'content' => "<script src=\"http://widgets.twimg.com/j/2/widget.js\"></script><script>
	new TWTR.Widget({ version: 2,type: 'profile',rpp: 5,interval: 30000,width: 'auto',height: 'auto',theme: {
    	shell: {background: 'none',color: '#c7c7c7'},
    	tweets: {background: '#252525', color: '#c7c7c7',links: '#ffffff'}
  	 },
  	features: {scrollbar: true,loop: false,live: true,hashtags: true,timestamp: true,avatars: false,behavior: 'all'}
	}).render().setUser('NFSKOru').start();
	</script>"
		);
	}
	
	
	//=========================================
	// Получение данных
	//=========================================
	
	// Заполнение массивов данных
	protected function getBlocks() {
		foreach ($this->bdrop as $cid => $col) {
			foreach ($col as $eid => $elm) {
				$elm = $this->$elm();
				$this->bdrop[$cid][$eid] = $elm;
			}
		}
	}
	
	// Последние сообщений (получение данных)
	protected function lastposts_data() {
		// $conf['active_ids'] ID разделов, которые вы хотите спрятать;
		$last_posts = array();
		$this->_DB->query("SELECT
					tid,title,last_poster_name,last_poster_id,last_post
					FROM ibf_topics
					WHERE forum_id NOT IN (".$this->_conf['active_ids'].")
					ORDER BY last_post DESC LIMIT 0,".$this->settings['lastposts']['count'].";" );
		while($row = $this->_DB->fetch_row()) {
			$row['last_post'] = gmdate('H:i',($row['last_post']+3*3600));
			$last_posts[] = $row;
		}
		return $last_posts;
	}
	
	// Последние файлы в архиве (получение данных)
	protected function lastfiles_data() {
		// $conf['active_num'] Количество 'последних файлов'
		$last_files = array();
		$this->_DB->query("SELECT
					f.id,f.name,f.count,f.created_by,f.created,m.name AS uname
					FROM `s_files_db` AS f
					LEFT JOIN `ibf_members` AS m ON (m.id=f.created_by)
					WHERE f.show='Y' ORDER BY f.id DESC LIMIT ".$this->settings['lastfiles']['count'].";");
		while($row = $this->_DB->fetch_row()) {
			$row['created'] = date_smart($row['created']);
			$last_files[] = $row;
		}
		return $last_files;
	}
	
	// Последние комментарии
	protected function lastcomments_data() {
		$last_com = array();
		if ($this->attr == 'my')
			$this->_DB->query("SELECT
					c.id,c.module AS module,c.text,m1.name AS name_p,m1.title AS title_p,m2.name AS title_f,m2.id AS name_f,c.user,n.name AS uname,c.date
					FROM `s_comments` AS c
					LEFT JOIN `s_pages` AS m1 ON (m1.id=c.module_id)
					LEFT JOIN `s_files_db` AS m2 ON (m2.id=c.module_id)
					LEFT JOIN `ibf_members` AS n ON (n.id=c.user)
					WHERE c.deleted='N' AND c.user = ".$this->_sdk_info[id]." AND (c.date,c.module_id) IN (SELECT max(date),module_id FROM `s_comments` GROUP BY `module_id`)
					ORDER BY `date` DESC LIMIT ".$this->settings['lastcomments']['count'].";");
		else if ($this->attr == 'me')
			$this->_DB->query("SELECT
					c.id,c.module AS module,c.text,m1.name AS name_p,m1.title AS title_p,m2.name AS title_f,m2.id AS name_f,c.user,n.name AS uname,c.date
					FROM `s_comments` AS c
					LEFT JOIN `s_pages` AS m1 ON (m1.id=c.module_id)
					LEFT JOIN `s_files_db` AS m2 ON (m2.id=c.module_id)
					LEFT JOIN `ibf_members` AS n ON (n.id=c.user)
					WHERE c.deleted='N' AND c.top IN ( SELECT id FROM `s_comments` WHERE deleted='N' AND user = ".$this->_sdk_info[id]." )
					ORDER BY `date` DESC LIMIT ".$this->settings['lastcomments']['count'].";");
		else
			$this->_DB->query("SELECT
					c.id,c.module AS module,c.text,m1.name AS name_p,m1.title AS title_p,m2.name AS title_f,m2.id AS name_f,c.user,n.name AS uname,c.date
					FROM `s_comments` AS c
					LEFT JOIN `s_pages` AS m1 ON (m1.id=c.module_id)
					LEFT JOIN `s_files_db` AS m2 ON (m2.id=c.module_id)
					LEFT JOIN `ibf_members` AS n ON (n.id=c.user)
					WHERE c.deleted='N' AND (c.date,c.module_id) IN (SELECT max(date),module_id FROM `s_comments` GROUP BY `module_id`)
					ORDER BY `date` DESC LIMIT ".$this->settings['lastcomments']['count'].";");
		while ($row = $this->_DB->fetch_row()) {
			$row['date'] = date_smart($row['date']);
			$last_com[] = $row;
		}
		return $last_com;
	}
	
	// Последние новости (получение данных)
	protected function lastnews_data() {
		// $conf['news_limit'] Количество 'последних новостей'
		$last_news = array();
		$this->_DB->query("SELECT
					p.post,t.tid,t.title,t.description,p.post_date
					FROM `ibf_topics` t
					LEFT JOIN `ibf_posts` p ON (t.tid=p.topic_id)
					WHERE t.forum_id IN (".$this->_conf['news_forum_id'].") and p.new_topic=1
					ORDER BY t.tid DESC LIMIT 0,".$this->settings['lastnews']['count'].";");
		while($row = $this->_DB->fetch_row()) {
			$row['post_date'] = strftime('%d.%m.%y', $row['post_date']);
			//Ставим всем внешним ссылкам <noindex> и rel="nofollow"
			$row['post']=preg_replace('#<a([^<]*)href=["\']http://(?!nfsko\.ru|www\.nfsko\.ru|files\.nfsko\.ru|images\.nfsko\.ru)([^"\']*)["\']([^<]*)>(.*)</a>#ismU', '<noindex><a$1href="http://$2"$3 rel="nofollow">$4</a></noindex>', $row['post']);
			//$row['post']=str_replace("&","&amp;",$row['post']);
			//$row['description']=str_replace("&","&amp;",$row['description']);
			$row['post']=str_replace("<br>","<br/>",$row['post']);
			$row['post']=str_replace(" alt='Прикреплённый рисунок'"," alt='Прикреплённый рисунок'/",$row['post']);
			$row['post']=str_replace("align=center","align=\"center\"",$row['post']);
			$row['post']=str_replace("align=right","align=\"right\"",$row['post']);
			$row['post']=str_replace("align=left","align=\"left\"",$row['post']);
			$last_news[] = $row;
		}
		return $last_news;
	}
	
	// Блокнот (получение данных)
	protected function notepad_data() {
		if ($this->_sdk_info) {
			$this->_DB->query("SELECT notes FROM `ibf_member_extra` WHERE id='".$this->_sdk_info['id']."';");
			if ($row = $this->_DB->fetch_row()) {
				$row['notes'] = preg_replace( "/<br>/", "\n", $row['notes'] );
				return $row['notes'];
			}
			else return "";
		} else return null;
	}
	
	protected function notepad_save() {
		if ($this->_sdk_info) {
			$this->_DB->query("SELECT id from `ibf_member_extra` WHERE id='".$this->_sdk_info['id']."'");
			if ($this->_DB->get_num_rows()) {
				$this->_DB->query("UPDATE `ibf_member_extra` SET notes='".$this->attr."' WHERE id='".$this->_sdk_info['id']."';");
			} else {
				$this->_DB->query("INSERT INTO `ibf_member_extra` (id, notes) VALUES ('".$this->_sdk_info['id']."', '".$this->attr."');");
			}
		}
	}
	
	
	/* (non-PHPdoc)
	 * @see \Controller\AbstractSiteController::getData()
	 */
	protected function getData() {
		if (in_array( $this->_sdk_info [id], array (
				1,
				281,
				24892,
				22753,
				21670,
				19515,
				8824,
				19473 
		) ) or ($this->_sdk_info ['posts'] > 3000)) {
			if (! defined( 'AJAX_M' )) {
				$p = $this->ShowGadgets();
				// Вывод ошибок и результата
				// print_r($sdk_info);
				echo $p->result;
			}
		} else {
			echo "<p align='center'>Страница находится в разработке.<br/>Доступ закрыт: нeдостаточно прав для просмотра.</p>";
		}

	}

}