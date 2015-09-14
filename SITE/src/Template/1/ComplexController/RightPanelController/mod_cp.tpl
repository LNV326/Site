{* Smarty *}
{* Правое меню: панель модератора*}

<tr><td class="mtr">Панель модератора</td></tr>
<tr><td><div style="font-size:7pt;margin-left:0.2cm;margin-right:0.1cm;text-align:left">
<font style="font-size:8pt;margin-bottom:0.1cm"><b>Файловый архив:</b></font><br/>
{if $files_count|default:0 == 0}нет не проверенных.{else}
    <b>Новых файлов: {$files_count}</b><br/><a href='http://files.{$conf.site_url}/index.php?view=allow'>Выполнить проверку</a>
{/if}
<br/><br/>
<font style="font-size:8pt;margin-bottom:0.1cm"><b>Галерея:</b></font><br/>
{if $images_count|default:0 == 0}нет не проверенных.{else}
    <b>Новых файлов: {$images_count}</b><br/><a href='http://images.{$conf.site_url}/index.php?view=allow'>Выполнить проверку</a>
{/if}
<br/><br/>
<font style="font-size:8pt;margin-bottom:0.1cm"><b>Очистить кэш:</b></font><br/>
<form action='/' method='post'>
<select name='clear_cache' class='forminput' style="width:70px; margin-right:5px;">
<option value="all">весь</option>
<option value="news">новостей</option>
</select><input class='forminput' type='submit' value='Очистить' style="width:65px;"></form>
{if $smrty.post.clear_cache=='all'}
Кэш полностью очищен!<br>
{elseif $smrty.post.clear_cache=='news'}
Кэш новостей очищен!<br>
{/if}
<br/>
<font style="font-size:8pt;margin-bottom:0.1cm"><b>ICQBot Load:</b></font><br/>
Если бот не в сети:<br/>
<a href="javascript:PopUp('/sources/bot/','Запуск ICQBot','250','100','0','1','1','1')">Запустить ICQBot</a>
</div></td></tr>