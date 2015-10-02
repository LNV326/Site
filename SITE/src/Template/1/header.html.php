<div class='row'>
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs"><?echo $logo;?></div>
</div>
<div class='row'>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<table border="0" cellspacing="0" cellpadding="0" class="top">
	<tr style="height: 22px;">
		<td width="45%"><p style="margin-left: 0.2cm; font-size: 8pt"><?
		if ($SDK->is_loggedin ()) {
			echo "<script type='text/javascript'>function log_out(){if (confirm('" . $lang [realy_exit] . "'))	{return true;}else{return false;}}</script>";
			echo '<b>' . $lang [login_hi] . '</b> <a href="http://' . $conf ['site_url'] . '/forum/index.php?showuser=' . $sdk_info [id] . '" target="_blank"><b>' . $sdk_info [name] . '</b></a> (<a href="http://' . $conf ['site_url'] . '/sources/auth.php?act=logout" onclick="return log_out()" target="_self">' . $lang ["exit"] . '</a>';
			if ($SDK->is_admin ()) {
				echo ' | <a href="http://' . $conf ['site_url'] . '/forum/admin.php" target="_blank"><b>Forum CP</b></a>';
			}
			if ($SDK->is_supermod ()) {
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
		<td width="55%" align="right"><p
				style="margin-right: 0.2cm; font-size: 8pt"><?
				if ($SDK->is_loggedin ()) {
					echo '<a href="http://' . $conf ['site_url'] . '/forum/index.php?act=UserCP" target="_blank"><b>' . $lang ["my_control"] . '</b></a> | <a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Search&amp;CODE=getnew" target="_blank">' . $lang ["new_posts"] . '</a> (<b>' . $SDK->get_num_new_posts () . '</b>) | <a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Msg&amp;CODE=01" target="_blank">' . $lang ["new_pms"] . '</a> (<b>' . $SDK->get_num_new_pms () . '/' . $SDK->get_num_total_pms () . '</b>)';
				} else {
					echo '<a href="http://' . $conf ['site_url'] . '/forum/index.php?act=Reg&amp;CODE=reval">' . $lang ["reval"] . '</a>';
				}
				?></p></td>
	</tr>
</table>
	</div>
</div>