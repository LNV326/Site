<?php
require_once '../src/NavigationManager.php';
NavigationManager::init($conf, $DB);
$mainMenu = NavigationManager::getMainMenu();
$menu = NavigationManager::getMenuCategories();

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
?>

<div class="ml_n" id="mainmenu-left">
	<nav class="sideblock-block">
		<div class="sideblock-header"><? echo $lang[menu]; ?></div>
		<ul class="sideblock-body">
			<? renderMenuBodyItems( $mainMenu ); ?>
		</ul>
	</nav>
<?php foreach ($menu as $category) {?>
	<nav class="sideblock-block">
		<div class="sideblock-header"
			onclick='menuLoad(this, "<?php echo 'm'.$category['id']; ?>")'>
			<?php echo $nfs->unconvert_html($category['name']); ?>
			<i <?php echo ($category['isOpen'] ? "class='expanded'" : ""); ?>></i>
		</div>
		<ul class="sideblock-body" id="<?php echo 'm'.$category['id']; ?>">
			<?php if ($category['isOpen']) renderMenuBodyItems($category['items']); ?>
		</ul>
	</nav>
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
if (!empty($tmp_html)) {
	?><div class="mtl_n">
		<b>Реклама</b>
	</div>
	<div style="padding: 0 10px; font-size: 9px;"><?echo $tmp_html;?></div><?
}
?>
</div>