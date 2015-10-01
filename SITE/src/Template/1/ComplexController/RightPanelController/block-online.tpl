{* Smarty *}
{* Правое меню: Кто онлайн *}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">{$lang.online}</div>
	<div class="sideblock-body">
		<div id='block-online'>
			{if $mem_count == 0}
			{$mem_str = 'Никого нет'}
			{/if}
			<h6>{$lang.in_site_forum}:</h6><p>{$mem_str}</p>		
			{if $mem_count > $conf.online_num or $que_count > 0}
			<p><a href='/forum/index.php?act=Online&amp;CODE=listall' target='_blank' title='Показать полный список'>Показать список</a></p>
			{/if}
			<p>Пользователей: {$mem_count}<br/>Гостей: {$que_count}</p>
		</div>
	</div>
</div>

