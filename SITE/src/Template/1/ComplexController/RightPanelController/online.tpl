{* Smarty *}
{* Правое меню: Кто онлайн *}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">{$lang.online}</div>
	<div class="sideblock-body">
<div style="font-size:7pt;margin-left:0.2cm;margin-right:0.1cm;text-align:left"><font style="font-size:8pt;margin-bottom:0.1cm"><b>{$lang.in_site_forum}:</b></font><br/>
{if $mem_count == 0}
{$mem_str = 'Никого нет'}
{/if}
<div style='margin-top:2pt;margin-bottom:2pt'>{$mem_str}</div>
{if $mem_count > $conf.online_num or $que_count > 0}
<a href='/forum/index.php?act=Online&amp;CODE=listall' target='_blank' title='Показать полный список'>Показать список</a><br/>
{/if}
Пользователей: {$mem_count}<br/>Гостей: {$que_count}
</div></div></div>

