{* Smarty *}
{* Правое меню: Поле авторизации *}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">Авторизация</div>
	<div class="sideblock-body">
		<form action="/sources/auth.php?act=login" method="post" name="theLoginForm" id="block-login">
			<div class="form-group">
    			<label for="username">Ваше имя:</label>
    			<input type="text" class="form-control" size="27" id="username" name="username" placeholder="Login">
  			</div>
  			<div class="form-group">
    			<label for="password">Ваш пароль:</label>
    			<input type="password" class="form-control" size="27" id="password" name="password" placeholder="Password">
  			</div> 	
			{* <input type="hidden" name="page_back" value="{$smarty.server.REQUEST_URI}"/> *}
			<button type="submit" class="btn btn-default center-block">Авторизоваться</button>
		</form>
	</div>
</div>