{* Smarty *}
{* Правое меню: Поле авторизации *}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">Авторизация</div>
	<div class="sideblock-body">
		<form action="/sources/auth.php?act=login" method="post" name="theLoginForm" id="theLoginForm" style="font-size:8pt;text-align:justify;margin-left:0.2cm;margin-right:0.2cm;margin-bottom:3pt">
		Ваше имя: <br/><input type="text" name="username" id="username" class="textinput" size="27" value="" style="width:144px"/><br/>
		Ваш пароль: <br/><input type="password" name="password" id="password" class="textinput" size="27" value="" style="width:144px"/><br/>
		{* <input type="hidden" name="page_back" value="{$smarty.server.REQUEST_URI}"/> *}
		<p align="center" style="margin-top:3pt"><input type="submit" value="Авторизоваться" class="forminput"/></p></form>
	</div>
</div>