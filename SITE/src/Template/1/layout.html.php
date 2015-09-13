<?php
echo '<?xml version="1" encoding="utf-8"?>';
global $pageManager;
?> 
<!-- quirks mode -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><? echo $nfs->unconvert_html($page[title]).($hide_title_info ? "" : " / ".$conf[site_title]); ?></title>
	<meta name="keywords" content="<? echo $conf[site_keywords]; ?>" />
	<meta name="description" content="<? echo $conf[banner_desc]; ?>" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="/style/js/bootstrap.min.js"></script>
	
	<!-- Add some JS -->
	<script language="JavaScript" type="text/javascript" src="http://<? echo $conf['site_url']; ?>/js/engine.js"></script>
	<script language="JavaScript" type="text/javascript" src="http://<? echo $conf['site_url']; ?>/js/load.js"></script>
	<!-- Add some CSS -->
	<link rel="stylesheet" href="/style/css/main_2015.css" type="text/css" />
</head>
<body>
	<div class="skeleton">
		<div class="skeleton1">
			<div class="skeleton2">
				<div class="skeleton3">
<?echo $logo;?>
					<table border="0" cellspacing="0" cellpadding="0" class="top">
						<tr style="height: 22px;">
							<td width="45%"><p style="margin-left: 0.2cm; font-size: 8pt"><?
							if ($SDK->is_loggedin()) {
								echo "<script type='text/javascript'>function log_out(){if (confirm('" . $lang [realy_exit] . "'))	{return true;}else{return false;}}</script>";
								echo '<b>' . $lang [login_hi] . '</b> <a href="http://' . $conf ['site_url'] . '/forum/index.php?showuser=' . $sdk_info [id] . '" target="_blank"><b>' . $sdk_info [name] . '</b></a> (<a href="http://' . $conf ['site_url'] . '/sources/auth.php?act=logout" onclick="return log_out()" target="_self">' . $lang ["exit"] . '</a>';
								if ($SDK->is_admin()) {
									echo ' | <a href="http://' . $conf ['site_url'] . '/forum/admin.php" target="_blank"><b>Forum CP</b></a>';
								}
								if ($SDK->is_supermod()) {
									echo ' | <a href="http://' . $conf ['site_url'] . '/admin.php" target="_blank"><b>Site CP</b></a></b> | <a href="http://' . $conf ['site_url'] . '/forum/index.php?act=modcp" target="_blank"><b>Mod CP</b></a>';
								}
								echo ')';
							} else {
								echo $lang [guest_hi] . ' ( <a href="http://' . $conf ['site_url'] . '/index.php?page=login">' . $lang [enter] . '</a> | <a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Reg" target="_balnk">' . $lang [reg] . '</a> )';
							}
							if ($conf ['on'] == 0) {
								echo ' - <font style="font-size:7pt;color:red"><b>' . $lang ["site_off"] . '</b></font>';
							}
							?></p></td>
							<td width="55%" align="right"><p style="margin-right: 0.2cm; font-size: 8pt"><?
							if ($SDK->is_loggedin()) {
								echo '<a href="http://' . $conf ['site_url'] . '/forum/index.php?act=UserCP" target="_blank"><b>' . $lang ["my_control"] . '</b></a> | <a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Search&amp;CODE=getnew" target="_blank">' . $lang ["new_posts"] . '</a> (<b>' . $SDK->get_num_new_posts() . '</b>) | <a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Msg&amp;CODE=01" target="_blank">' . $lang ["new_pms"] . '</a> (<b>' . $SDK->get_num_new_pms() . '/' . $SDK->get_num_total_pms() . '</b>)';
							} else {
								echo '<a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Reg&amp;CODE=reval">' . $lang ["reval"] . '</a>';
							}
							?></p></td>
						</tr>
					</table>
					
					<table cellspacing="0" cellpadding="0" style='width: 100.0%; border: none'>
						<!-- Main area -->
						<tr style='background-color: #323232'>
							<td class="ml" valign="top" rowspan="2"><? include "../src/Template/" . $style_id . "/mainmenu_left.php"; ?></td>
							<td id='content'><? $pageManager->include_page(); ?></td>
							<td class="mr" valign="top" rowspan="2"><? include "../src/Template/" . $style_id . "/mainmenu_right.php";?></td>
						</tr>
						<tr>
							<td rowspan="2" style="width:100%;background: #000000 url(<? echo $bg ;?>); vertical-align:bottom;"><?
							if ($conf ['adver_site_on'] == 1) {
								echo "<div style='margin:10px 25px;position:relative;display:block;text-align:center;'><noindex>" . $conf ['adver_site_html'] . "</noindex></div>";
							} else {
								echo "<p align=\"center\" class=\"small\" style=\"margin-bottom:3pt\">" . $conf ['site_name'] . " © " . $conf ['site_start_year'] . " - " . date( Y ) . "</p>\n";
							}
							if ($conf ['debug_on'] == 1 and $SDK->is_admin()) {
								echo "<p align=\"center\" class=\"small\" style=\"margin-bottom:3pt\">[ ";
								echo "<span style='margin:0px 5px'>Время генерации: " . sprintf( "%.4f", $Debug->endTimer() ) . "</span>";
								echo "<span style='margin:0px 5px'>Запросов к БД: " . $DB->query_count . "</span>";
								echo "<span style='margin:0px 5px'>Время выполнения запросов: " . sprintf( '%.5f', $GLOBALS ['mysql'] ['totalQueryTime'] ) . " sec</span>";
								echo "<span style='margin:0px 5px'>Использовано памяти: <b>" . number_format( round( memory_get_usage() / 1024 ) ) . " Kb</b></span>";
								echo "<span style='margin:0px 5px'>Максимум (пик): <b>" . number_format( round( memory_get_peak_usage() / 1024 ) ) . " Kb</b></span>";
								echo " ]</p>\n";
							}
							?>
</td>
						</tr>
					</table>
<?
echo $bottom;
if (($SDK->get_num_new_pms() != 0) and ($sdk_info ['view_pop'] == '1')) {
	echo "<script language=\"javascript\" type=\"text/javascript\"><!--
PopUp('/modules/view_new_pms.php','New_PMS','550','200','0','1','1','1');
//--></script>\n";
}
?>
</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php

echo $conf [count_bottom];
// Показываем ошибки MYSQL для главного администратора
if (($sdk_info [id] == 1 or $sdk_info [id] == 281) and $show_log) {
	foreach ( $GLOBALS ['mysql'] ['query_log'] as $log ) {
		echo '<div style="margin:5px; padding:5px; font-size:9px;">' . $log ['query'] . '<br>выполнен за ' . $log ['time'] . '</div>';
	}
	$DB->error_print( TRUE );
}
?>