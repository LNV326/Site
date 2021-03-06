<?php
// require_once '../src/NavigationManager.php';
// NavigationManager::init($conf, $DB);
// $mainMenu = NavigationManager::getMainMenu();
// $menu = NavigationManager::getMenuCategories();

function renderMenuBodyItems($items) {
	global $nfs, $langinfo, $style_id;
	foreach ( $items as $item ) {
		$row_link = ($item ['type'] == 'link') ? $item ['url'] : "/index.php?page=" . $nfs->unconvert_html( $item ['url'] );
		$row_target = ($item ['open_new'] == '1') ? " target='_blank'" : "";
		$item_noindex = ($item ['type'] == 'link' || $item ['noindex']) ? true : false;
		$item_text = $nfs->unconvert_html( (!empty($item['info'])) ? $item['info'] : $item[$langinfo['ldir']] );
		?>
<li>
		<?php if (!empty($item['image'])) { ?>
			<div class="smalllink">
				<noindex> <a href="<?php echo $item['image_link']; ?>" rel="nofollow"
					title="<?php echo $item['image_title']; ?>"> <img
					src="/style/<?php echo $style_id; ?>/img/<?php echo $item['image']; ?>"
					border="0" width="21px" height="11px" alt="" />
				</a> </noindex>
			</div>
		<?php } echo ($item_noindex ? "<noindex>" : ""); ?>
<a href="<?php echo $nfs->unconvert_html($row_link); ?>" <?php echo $row_target; ?> <?php echo ($item_noindex ? " rel='nofollow'" : ""); ?> <?php echo ($item['new'] == '1') ? 'new' : ''; ?> title="<?php echo $item_text; ?>"><?php echo ($item['isBold'] ? '<b>'.$item_text.'</b>' : $item_text); ?></a>
		<?php echo ($item_noindex ? "</noindex>" : ""); ?>
</li>
<?php
	}
}

function renderUserProfileInfo() {
	global $SDK, $conf, $lang;
	if ($SDK->is_loggedin ()) {
		echo '
		<li><a href="http://' . $conf ['site_url'] . '/forum/index.php?act=UserCP" target="_blank"><b>' . $lang ["my_control"] . '</b></a></li>
		<li><a href="http://' . $conf ['site_url'] . '/sources/auth.php?act=logout" onclick="return log_out()" target="_self"><b>' . $lang ["exit"] . '</b></a></li>
		<li><a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Search&amp;CODE=getnew" target="_blank">' . $lang ["new_posts"] . '</a> (<b>' . $SDK->get_num_new_posts () . '</b>)</li>
		<li><a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Msg&amp;CODE=01" target="_blank">' . $lang ["new_pms"] . '</a> (<b>' . $SDK->get_num_new_pms () . '/' . $SDK->get_num_total_pms () . '</b>)</li>';
		if ($SDK->is_admin ()) {
			echo '<li><a href="http://' . $conf ['site_url'] . '/forum/admin.php" target="_blank"><b>Forum CP</b></a></li>';
		}
		if ($SDK->is_supermod ()) {
			echo '
			<li><a href="http://' . $conf ['site_url'] . '/admin.php" target="_blank"><b>Site CP</b></a></b></li>
			<li><a href="http://' . $conf ['site_url'] . '/forum/index.php?act=modcp" target="_blank"><b>Mod CP</b></a></li>';
		}
	} else {
		echo '
		<li><a href="http://' . $conf ['site_url'] . '/index.php?page=login">' . $lang ["enter"] . '</a></li>
		<li><a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Reg" target="_balnk">' . $lang ["reg"] . '</a></li>
		<li><a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Reg&amp;CODE=reval">' . $lang ["reval"] . '</a></li>';
	}
}
?>

<nav class="ml_n" id="mainmenu-left">
	<div class="sideblock visible-sm visible-xs hidden-lg hidden-md">
		<div class="sideblock-header"><? 
		if ($SDK->is_loggedin ()) {
			echo '<b>' . $lang ['login_hi'] . '</b> <a href="http://' . $conf ['site_url'] . '/forum/index.php?showuser=' . $sdk_info ['id'] . '" target="_blank"><b>' . $sdk_info ['name'] . '</b></a>';
		} else {
			echo $lang [guest_hi];
		}
		?></div>
		<ul class="sideblock-body"><? renderUserProfileInfo(); ?></ul>
	</div>
	<div class="sideblock">
		<div class="sideblock-header"><? echo $lang[menu]; ?></div>
		<ul class="sideblock-body"><? renderMenuBodyItems( $out['mainMenu'] ); ?></ul>
	</div>
<?php foreach ($out['menu'] as $category) {?>
	<div class="sideblock">
		<div class="sideblock-header" onclick='menuLoad(this, "<?php echo 'm'.$category['id']; ?>")'>
			<?php echo $nfs->unconvert_html($category['name']); ?>
			<i <?php echo ($category['isOpen'] ? "class='expanded'" : ""); ?>></i>
		</div>
		<ul class="sideblock-body" id="<?php echo 'm'.$category['id']; ?>">
			<?php if ($category['isOpen']) renderMenuBodyItems($category['items']); ?>
		</ul>
	</div>
<?php }
/*if ($sape_show) {
 $a = $sape->return_links();
 if (!empty ($a)){
 ?><div class="mtl_n"><b>Реклама</b></div>
 <div style="padding:0 10px; font-size:9px;"><?
 echo $a;
 ?></div><?
 }
}*/
//Блок рекламы
$tmp_html='';
$cur_page=$_GET['page'];
if (empty($cur_page)) {
	$tmp_html='<a href="http://www.ekmap.ru">EkMap.ru</a> &ndash; лучшие достопримечательности, памятники, музеи и другие интересные места города Екатеринбурга.';
} elseif ($cur_page=='c_person') {
	$tmp_html='<a href="http://www.ekmap.ru/map">Карта интересных мест</a> и достопримечательностей города Екатеринбурга';
} elseif ($cur_page=='hs_secrets') {
	$tmp_html='<a href="http://www.ekmap.ru/sight">Достопримечательности Екатеринбурга</a> – парки, памятники, музеи';
} elseif ($cur_page=='info') {
	$tmp_html='<a href="http://www.ekmap.ru/responses">Рассказы жителей города</a> о достопримечательностях Екатеринбурга';
} elseif ($cur_page=='files') {
	$tmp_html='<a href="http://www.ekmap.ru/parks">Парки Екатеринбурга</a>';
}
//require_once($_SERVER['DOCUMENT_ROOT'].'/sources/'.TRUSTLINK_USER.'/trustlink.php');
//$o['charset'] = 'UTF-8';//кодировка сайта
//$trustlink = new TrustlinkClient($o);
//unset($o);
//echo $trustlink->build_links();
if (!empty($tmp_html)) { ?>
<div class="sideblock">
	<div class="sideblock-header">Реклама</div>
	<div class="sideblock-body">
		<div style="padding: 0 10px; font-size: 9px;"><?echo $tmp_html;?></div>
	</div>
</div>
<? } ?>
</nav>