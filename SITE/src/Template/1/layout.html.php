<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title><? echo $nfs->unconvert_html($page[title]).($hide_title_info ? "" : " / ".$conf[site_title]); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="<? echo $conf[site_keywords]; ?>" />
<meta name="description" content="<? echo $conf[banner_desc]; ?>" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/style/js/bootstrap.min.js"></script>

<!-- Add some JS -->
<script language="JavaScript" type="text/javascript"
	src="http://<? echo $conf['site_url']; ?>/js/engine.js"></script>
<script language="JavaScript" type="text/javascript"
	src="http://<? echo $conf['site_url']; ?>/js/load.js"></script>
<!-- Add some CSS -->
<link rel="stylesheet" href="/style/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="/style/css/bs-structure.css" type="text/css" />
</head>
<body>
	<div class="container" id="container-main">
		<header class="row" id="header">
			<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><? include "../src/Template/" . $style_id . "/header.html.php"; ?></div>
		</header>
		<div class="row">
			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 visible-lg visible-md hidden-sm hidden-xs" id='panel-left'><? include "../src/Template/" . $style_id . "/mainmenu_left.php";?></div>
			<!-- Основное содержимое веб-страницы -->
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" id='panel-center'>
				<div class="row">
					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' id='adver-top'>
					<?php if ($conf ['adver_site_top_on'] == 1) {
						// if ($page[name]<>"news") {
						/*
						 * ?>
						 * <script type="text/javascript"><!--
						 * google_ad_client = "ca-pub-8369190706828575";
						 * google_ad_slot = "7094441647";
						 * google_ad_width = 468;
						 * google_ad_height = 60;
						 * //-->
						 * </script>
						 * <script type="text/javascript"
						 * src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						 * </script>
						 * <?
						 */
						// echo "<p style=\"margin:5px 5px 5px -364px; text-align:center; height:90px; width:728px; left:50%; position:relative;\">";
						echo "<p style=\"margin:5px 5px 5px -234px; text-align:center; height:60px; width:468px; left:50%; position:relative;\">";
						echo $conf ['adver_site_top_html'];
						echo "</p>";
						// } else {
						/*
						 * ?>
						 * <div style="width:100%;margin:5px 0 5px 0;"><object>
						 * <embed src="/files/roxen.swf" width="100%" height="55" style="border:0px;height:55px;"></embed>
						 * </object></div>
						 * <?
						 */
						// }
					} ?>
					</div>
				</div>
				<div class="row">
					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' id="content"> <? $pageManager->include_page(); ?></div>
				</div>
				<div class="row">
					<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' id='adver-bottom'>
						<? if ($conf ['adver_site_on'] == 1) {
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
						} ?>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 hidden-sm hidden-xs" id='panel-right'><? include "../src/Template/" . $style_id . "/mainmenu_right.php";?></div>							
		</div>
		<footer class='row' id='footer'>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<? include "../src/Template/" . $style_id . "/footer.html.php";
if (($SDK->get_num_new_pms() != 0) and ($sdk_info ['view_pop'] == '1')) {
	echo "<script language=\"javascript\" type=\"text/javascript\"><!--
PopUp('/modules/view_new_pms.php','New_PMS','550','200','0','1','1','1');
//--></script>\n";
}
?>
			</div>
		</footer>
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