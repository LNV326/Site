{* Smarty *}
{* Правое меню: Поле поиска *}

<tr><td class="mtr">{$lang.search_title}</td></tr>
<tr><td>
<form action="/index.php?page=search&amp;search_in=topics&amp;start=1" method="post" style="font-size:8pt;text-align:justify;margin-left:0.2cm;margin-right:0.2cm;margin-bottom:3pt">
{$lang.search_text}<br/><input type="text" name="keywords" class="textinput" size="27" value="" style="width:144px"/><br/>
<p align="center" style="margin-top:3pt"><input type="submit" value="{$lang.search_title}" class="forminput"/></p></form>
</td></tr>