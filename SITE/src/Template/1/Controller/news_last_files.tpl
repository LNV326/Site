{* Smarty *}
{* Последние файлы в архиве *}

<table cellpadding='1' cellspacing='0' border='0'>{foreach from=$last_files item=file}<tr>
<td style="font-size:9px"><a href="http://files.{$conf.site_url}/download.php?go={$file.id}" title="Скачать {$file.name}">{$file.shortname}</a> - ({$file.count})</td>
</tr>{/foreach}</table>