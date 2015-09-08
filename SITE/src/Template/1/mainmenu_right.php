<table class="mr" cellspacing="0" cellpadding="0" style="width: 165px; border: none">
<?php
use Controller\RightPanelController;
$rpc = new RightPanelController( $em, $DB, $conf, $smarty, $ibforums, $INFO, $std, $nfs, $sdk_info, $style_id, $lang, $SDK, $admin );
$rpc->index();

?><tr>
		<td class="mtr">Реклама</td>
	</tr>
	<tr>
		<td>
			<div style='margin: 10px 5px'><?
			echo $conf ['adver_site_rightmenu_html'];
			?></div><?
if ($sape_show) {
	$a = $sape->return_links();
	if (! empty( $a )) {
		?><div style='padding: 0px 10px; font-size: 9px;'><?echo $a;?>&nbsp;</div><?
	}
}
?></td>
	</tr>
	<tr>
		<td><img src="<? echo $empty; ?>" width="165px" height="1px" alt="" border="0" /></td>
	</tr>
</table>
