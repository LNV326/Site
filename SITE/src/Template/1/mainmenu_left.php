<div class="ml_n" id="mainmenu-left">
	<nav class="dropdown open-permanent">
		<div class="dropdown-toggle" data-toggle="dropdown"><b><? echo $lang[menu]; ?></b></div>
		<ul class="dropdown-menu navmenu-nav">
<?
// include "sources/mainmenu_items.php";
require_once '../src/NavigationManager.php';
NavigationManager::init($conf, $DB);
$main_menu = NavigationManager::getMainMenu();


foreach ($main_menu as $item) {
	$blank = $item['blank']=="1" ? " target='_blank'" : "";
	$item_text = $item[$langinfo['ldir']];
	$item_text_link = $item['b']==1 ? "<b>".$item_text."</b>" : $item_text;
	$item_noindex = $item['noindex'] ? true : false;
	
	//Вывод
	?>
	<li>
		<?php if (!empty($item['image'])) { ?>
		<div class="smalllink"><noindex>
			<a href="<?php echo $item['image_link']; ?>" rel="nofollow" title="<?php echo $item['image_title']; ?>">
				<img src="/style/<?php echo $style_id; ?>/img/<?php echo $item['image']; ?>" border="0" width="21px" height="11px" alt=""/>
			</a>
		</noindex></div>
		<?php } ?>
		<a href="<?php echo $item['link']; ?>" <?php echo $blank.($item_noindex ? ' rel="nofollow"' : '')?> title="<?php echo $item_text; ?>"><?php echo $item_text_link; ?></a>
	</li>
	<?php 
}
?>
		</ul>
		<div style='clear:both;'></div>
	</nav>
<?php 

// include "sources/left_menu.php";
// echo leftmenu_go();
$menu = NavigationManager::getMenuCategories();

foreach ($menu as $category) {?>
<nav class="dropdown">
	<div class="dropdown-toggle <?php echo ($category['isOpen'] ? "open-permanent" : ""); ?>" data-toggle="dropdown" onclick='menuLoad(this, <?php echo 'm'.$category['id']; ?>)'>
		<b><?php echo $nfs->unconvert_html($category['name']); ?></b>
	</div>
	<ul class="dropdown-menu navmenu-nav" id="<?php echo 'm'.$category['id']; ?>">
	<?php foreach ($category['items'] as $item) {
		$row_link = ($item['type'] == 'link') ?	$item['url'] : "/index.php?page=".$nfs->unconvert_html($item['url']);
		$row_target = ($item['open_new'] == '1') ? " target='_blank'" : ""; ?>
		<li>
			<?php echo ($item['type']=='link' ? "<noindex>" : ""); ?>
				<a href='<?php echo $nfs->unconvert_html($row_link); ?>' <?php echo $row_target; ?><?php echo ($item['type']=='link') ? " rel='nofollow'" : ""; ?> <?php echo ($item['new'] == '1') ? 'new' : ''; ?>>
					<?php echo $nfs->unconvert_html($item['info']); ?>
				</a>
			<?php echo ($item['type']=='link' ? "</noindex>" : ""); ?>
		</li>		 
	<?php } ?>
	</ul>
	<div style='clear:both;'></div>
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
	?><div class="mtl_n"><b>Реклама</b></div>
<div style="padding:0 10px; font-size:9px;"><?echo $tmp_html;?></div><?
}
?>
</div>