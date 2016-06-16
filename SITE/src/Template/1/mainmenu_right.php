<aside id='rightpanel'>
<?php
foreach ($out as $rightBlock) {
	echo $rightBlock;
}
?>
<div class="sideblock-block">
	<div class="sideblock-header">Реклама</div>
	<div class="sideblock-body">
		<div style='margin: 10px 5px'><? echo $conf ['adver_site_rightmenu_html']; ?></div><?
if ($sape_show) {
	$a = $sape->return_links();
	if (! empty( $a )) { ?>
		<div style='padding: 0px 10px; font-size: 9px;'><? echo $a; ?>&nbsp;</div>
<?
	}
}
?>
	</div>
</div>
<img src="<? echo $empty; ?>" width="165px" height="1px" alt="" border="0" />
</aside>