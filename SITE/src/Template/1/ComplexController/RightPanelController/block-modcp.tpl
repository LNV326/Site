{* Smarty *}
{* Правое меню: панель модератора*}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">Панель модератора</div>
	<div class="sideblock-body">
		<div id='block-modcp'>
			<h6>Файловый архив:</h6>
			{if $files_count|default:0 == 0}<p>нет не проверенных</p>{else}
		    <p>Новых файлов: {$files_count}</p>
		    <p><a href='http://files.{$conf.site_url}/index.php?view=allow'>Выполнить проверку</a></p>
			{/if}
			<h6>Галерея:</h6>
			{if $images_count|default:0 == 0}<p>нет не проверенных</p>{else}
		    <p>Новых файлов: {$images_count}</p>
		    <p><a href='http://images.{$conf.site_url}/index.php?view=allow'>Выполнить проверку</a></p>
			{/if}
			<h6>Очистить кэш:</h6>
			<form action='/' method='post' class='form-inline'>
				<select name='clear_cache' class="form-control">
					<option value="all">весь</option>
					<option value="news">новостей</option>
				</select>
				<button type="submit" class="btn btn-default">Очистить</button>
			</form>
			{if $smrty.post.clear_cache=='all'}
			<p>Кэш полностью очищен!</p>
			{elseif $smrty.post.clear_cache=='news'}
			<p>Кэш новостей очищен!</p>
			{/if}
			<h6>ICQBot Load:</h6>
			<p><a href="javascript:PopUp('/sources/bot/','Запуск ICQBot','250','100','0','1','1','1')">Запустить ICQBot</a></p>
		</div>
	</div>
</div>