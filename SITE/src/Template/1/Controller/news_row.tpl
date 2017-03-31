{* Smarty *}
{* Модуль: Новости *}

{* Пока закомментил onclick="returnNews('{$smarty.section.pg.index}')" class="ajaxlink" *}

{* Ссылки на другие страницы *}
{capture name=pages}{section name=pg loop=6 start=1}
{if $smarty.section.pg.index != 1}|{/if}
<a href="?news={$smarty.section.pg.index}">{if $smarty.section.pg.index == $news_page}<b>{$smarty.section.pg.index}</b>{else}{$smarty.section.pg.index}{/if}</a>{/section}{/capture}

{* Новости *}

<div class='row news-topline'>
	<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>{$lang.last_news}:</div>
	<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>{$lang.page}: [{$smarty.capture.pages}]</div>
</div>

<div class='row news'>
{foreach from=$news item=post}
<div class='col-lg-12 col-md-12 col-sm-6 col-xs-12 newsblock'>
	<div class='row newsblock-header'>
		<div class='col-lg-10 col-md-10 col-sm-10 col-xs-10'>{$post.topic_title}</div>
		<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>{$post.topic_time}</div>
	</div>
	<div class='row newsblock-body'>
		<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><index>{$post.content}</index></div>
	</div>
	<div class='row newsblock-footer'>
		<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>
			{$lang.posted}: <a href="{$conf.news_forum_path}index.php?showuser={$post.author_id}" target="_blank">{$post.author_name}</a> | {$lang.source}: <noindex><a href="http://{$post.description}/" target="_blank">{$post.description}</a></noindex>
		</div>
		<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>
			<a href="{$conf.news_forum_path}index.php?showtopic={$post.topic_id}" target="_blank">{$lang.comments}</a> ({$post.comments_count})
		</div>		
	</div>
</div>
{/foreach}
</div>

{if $user == 281}<!-- AddThis Button BEGIN -->
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e89eadf4e56b4d0"></script>
<!-- AddThis Button END -->{/if}

<div class='row news-footerline'>
	<div class='col-lg-8 col-md-8 col-sm-8 col-xs-8'>{$lang.page}: [{$smarty.capture.pages}]</div>
	<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'><a href="/modules/rss.php" target="_blank">{$lang.rss_news}</a> ::: <a href="/forum/index.php?act=SF&amp;f={$conf.news_forum_id}" target="_blank">{$lang.archive}</a>{$subscribe_link}</div>
</div>