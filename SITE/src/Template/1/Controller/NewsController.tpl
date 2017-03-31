{* Smarty *}
{* Центральная часть страницы *}

{*
{if $conf.active_on == 1}
<table align='center' class='tl' cellspacing='0' cellpadding='0'><tr>
<td><img src='style/{$style_id}/img/tll.gif' alt="" border="0"/></td>
<td width='62%'>&nbsp;<b><a href="/forum/index.php?act=Search&CODE=getactive">{$lang.last_active_posts}</a></b></td>
<td width='38%'><b><a href="http://files.{$conf.site_url}/">{$lang.last_files_in_archive}</a></b></td>
<td><img src="style/{$style_id}/img/tlr.gif" alt="" border="0"/></td></tr></table>
<table align='center' cellpadding='1' cellspacing='0' border='0' width='98%'>
<tr><td width='58%'>  
{$last_posts}                                           
</td><td width='2%'></td><td width='40%'>     
{$last_files}               
</td></tr></table>        
{/if}
<div id="lnews" align="center">
{$last_news}
</div>
*}

<div class='container-fluid'>
	{* Last active... something *}
	{if $conf.active_on == 1}
	<div class='row'>
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12' id='lastposts'>
			<div class='row header'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><a href="/forum/index.php?act=Search&CODE=getactive">{$lang.last_active_posts}</a></div>
			</div>
			<div class='row content'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>{$last_posts}</div>
			</div>
		</div>
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12' id='lastfiles'>
			<div class='row header'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><a href="http://files.{$conf.site_url}/">{$lang.last_files_in_archive}</a></div>
			</div>
			<div class='row content'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>{$last_files}</div>
			</div>
		</div>
	</div>
	{/if}
	{* Last news *}
	<div class='row' id='lnews'>
		<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>{$last_news}</div>
	</div>
</div>