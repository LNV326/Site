{* Основная страница *}
<script type="text/javascript" src="http://nfsko.ru/js/modules/articles.js"></script>
<table align="center" class="newst" cellspacing="0" cellpadding="0"><tr>
<td><img src="{$tll}" alt="" border="0"/></td>
<td width="98%">&nbsp;<index><b>{$page.title}</b></index> [<a href="admin.php?adsess=&set=articles_edit&type={$page.editor}&id={$page.id}">Редактировать</a>][<a onclick="edit_page('{$page.editor}', {$page.id}); return false;">Быстрое редактирование</a>]</td>
<td><img src="{$tlr}" alt="" border="0"/></td>
</tr></table>
<div id="status" style="display:none; margin:5px;"></div>
<div id="htmlpage" style="display:block;">{$page.html_page}</div>
{* Редактор *}
<div id="editor" style="display:none;border: 1px solid rgb(85,85,85);width:98%;">
<p align="center">
<textarea id="editarea" style="width: 100%; height: 300px;" class="textinput" tabindex="3" name="Post" rows="18" cols="100"></textarea>
<div style="text-align:right;"><input type="submit" class="forminput" onclick="save_page('{$page.editor}', {$page.id}); return false;" value="Сохранить"> <input type="reset" class="forminput" onclick="reset_page(); return false;" value="Отмена"></div>
</p>
</div>
{* Редактор (конец) *}
<div style="font-size:8pt;margin-left:0.2cm;margin-top:3pt"><img src="{$lil}" alt="" border="0"/> Навигация: <a href="http://{$conf.site_url}" title="{$conf.banner_desc}">{$conf.site_name}</a> -=- <a href='index.php?page={$page.root_page}'>{$page.root_page_title}</a> -=- {$page.title}</div>
<table align="center" cellspacing="0" cellpadding="0" style="width:96%;font-size:7pt;margin-top:5pt;margin-bottom:5pt">
<tr><td width="35%">Страница создана: {$page.created}</td></tr>
<tr><td width="35%">Обращений к странице: <b>{$page.counts}</b></td>
{if $page.page_info != ''}<td width="65%" align="right">{$page.page_info}</td>{/if}
</tr></table>