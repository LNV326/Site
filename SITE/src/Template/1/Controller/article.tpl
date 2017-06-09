{* Основная страница *}
<table align="center" class="newst" cellspacing="0" cellpadding="0"><tr>
<td><img src='style/{$style_id}/img/tll.gif' alt="" border="0"/></td>
<td width="98%">&nbsp;<index><b>{$page.title}</b></index></td>
<td><img src='style/{$style_id}/img/tlr.gif' alt="" border="0"/></td>
</tr></table>
<div id="htmlpage" style="display:block;">{$page.html_page}</div>
<div style="font-size:8pt;margin-left:0.2cm;margin-top:3pt"><img src="{$lil}" alt="" border="0"/> Навигация: <a href="http://{$conf.site_url}" title="{$conf.banner_desc}">{$conf.site_name}</a> -=- <a href='index.php?page={$page.root_page}'>{$page.root_page_title}</a> -=- {$page.title}</div>
<table align="center" cellspacing="0" cellpadding="0" style="width:96%;font-size:7pt;margin-top:5pt;margin-bottom:5pt">
<tr><td width="35%">Страница создана: {$page.created}</td></tr>
<tr><td width="35%">Обращений к странице: <b>{$page.counts}</b></td>
{if $page.page_info != ''}<td width="65%" align="right">{$page.page_info}</td>{/if} 
</tr></table>