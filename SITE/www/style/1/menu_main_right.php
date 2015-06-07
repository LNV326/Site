<table class="mr" cellspacing="0" cellpadding="0" style="width:165px;border:none">
<?php

//Поле авторизации
if (($conf['menur_login']==1) and (!$SDK->is_loggedin())){
    $smarty->cache_lifetime = -1;
    $smarty->display('right_menu/login.tpl');
}

//Кто онлайн
if ($conf['menur_online'] == 1){ 
    $smarty->cache_lifetime = 60;  //На 60 секунд   
    if (!$smarty->is_cached('right_menu/online.tpl')) {
        $to_echo = array();
        $DB->query("SELECT s.member_name, s.member_id FROM ibf_sessions s WHERE s.member_id <> 0 AND s.running_time > " . (time() - 900) . " ORDER BY 'running_time' DESC LIMIT 0,".$conf['online_num'].";");
        while($out = $DB->fetch_row()) {
            $to_echo[] = "<a href='/forum/index.php?showuser=".$out['member_id']."' target='_blank'>".$out['member_name']."</a>";
        }
        $to_echo = implode(", ", $to_echo);
        $DB->query("SELECT count(member_id) as count FROM ibf_sessions WHERE member_id <> 0 AND running_time > " . (time() - 900)." LIMIT 0,1");
        $row = $DB->fetch_row();
        if ($row['count'] > $conf['online_num']) $to_echo .= '...';
        $smarty->assign('mem_count', $row['count']);
        $smarty->assign('mem_str', $to_echo);
        $DB->query("SELECT count(member_id) as count FROM ibf_sessions WHERE member_id = 0 AND running_time > " . (time() - 900)." LIMIT 0,1");
        $row = $DB->fetch_row();
        $smarty->assign('que_count', $row['count']);    
    }
    $smarty->display('right_menu/online.tpl');
}

//Панель модератора
if (($sdk_info[id]==$admins['root']) or ($SDK->is_small_siteadmin($sdk_info['id']) == TRUE) or ($SDK->is_full_siteadmin($sdk_info['id']) == TRUE)) {
    $smarty->cache_lifetime = 10;  //Раз в минуту
    if (!$smarty->is_cached('right_menu/mod_cp.tpl')) {
    	$repo = $em->getRepository('Entity\EntitySGalleryImages');
    	$img_count = $repo->getCountForReview();
        $smarty->assign('images_count', $img_count);
        $repo = $em->getRepository('Entity\EntitySFilesDb');
        $files_count = $repo->getCountForReview();
        $smarty->assign('files_count', $files_count);        
    }
    $smarty->display('right_menu/mod_cp.tpl');
}

//Случайный скриншот
if ($conf['menur_gallery'] == 1){
    $vid = rand(1,5);
    $smarty->cache_lifetime = 60;  //На 60 секунд
    if (!$smarty->is_cached('right_menu/rnd_screen.tpl', $vid)) {
        //Создание списка файлов
        if (!empty($conf['gallery_dir']) and $conf['gallery_dir']<>'0') {
            $sql_add="subcat IN (".$conf['gallery_dir'].") and ";
        } else $sql_add="";
        $repo = $em->getRepository('Entity\EntitySGalleryImages');
        $image = $repo->getRandomImage();
        //Получение данный о подкатегории
        $subcat_row = $image->getSubcatVal();
        //Размер изображения
        $size_px = getimagesize($conf[images_path]."gallery/".$subcat_row->getDirName()."/thumbs/".$image->getFilename());
        if (!$size_px[0]) {
            $size_px[0]=0;
            $size_px[1]=0;
        }
        $smarty->assign('image', $image->_toArray());
        $smarty->assign('subcat_row', $subcat_row->_toArray());
        $smarty->assign('size_px', $size_px);
    }
    $smarty->display('right_menu/rnd_screen.tpl',$vid);   
}

//Поле поиска
if ($conf['menur_search'] == 1){
    $smarty->cache_lifetime = -1;
    $smarty->display('right_menu/search.tpl');   
}

//Поле доп. проектов 
$smarty->cache_lifetime = -1;
$smarty->display('right_menu/progects.tpl');   

//Таймер
if ($conf['menur_time'] == 1){
    $smarty->cache_lifetime = 86400;  //Раз в сутки
    $smarty->display('right_menu/timer.tpl');    
}

//Остальное
$smarty->cache_lifetime = 86400;  //Раз в сутки
$smarty->display('right_menu/other.tpl');

//Удаляем используемые в модуле Smarty-переменные  
$smarty->clear_assign(array('mem_count','que_count','mem_str','img_count','image','subcat_row','size_px')); 

?><tr><td class="mtr">Реклама</td></tr>
<tr><td>
<div style='margin:10px 5px'><?
echo $conf['adver_site_rightmenu_html'];
?></div><?
if ($sape_show) {
	$a = $sape->return_links();
	if (!empty ($a)){
	?><div style='padding:0px 10px; font-size:9px;'><?echo $a;?>&nbsp;</div><?
	}
}
?></td></tr>
<tr><td><img src="<? echo $empty; ?>" width="165px" height="1px" alt="" border="0"/></td></tr>
<?
	if ($sdk_info[id]==1 OR $sdk_info[id]==281) {
		echo "<tr><td>От начала генерации:<br/>".$Debug->endTimer()."</td></tr>";
	}
?>
</table>
