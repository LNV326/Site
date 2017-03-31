{* Smarty *}
{* Последние сообщения на форуме *}

<table cellpadding='1' cellspacing='0' border='0' width='100%'>
{foreach from=$last_posts item=post}<tr>
<td align="left" style="font-size:9px"><img src="{$lil}" alt="" border="0"/> <a href="/forum/index.php?showtopic={$post.topic_id}&amp;view=getnewpost" target="_blank">{$post.topic_title}</a></td>
<td align="right" style="font-size:9px"><a href="/forum/index.php?showuser={$post.author_id}" target="_blank">{$post.author_name}</a> - {$post.post_date}&nbsp;</td>
</tr>{/foreach}
</table>