{* Smarty *}
{* Правое меню: Поле поиска *}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">{$lang.search_title}</div>
	<div class="sideblock-body">
		<form action="/index.php?page=search&amp;search_in=topics&amp;start=1" method="post" id='block-search'>
			<div class="form-group">
    			<label for="username">{$lang.search_text}</label>
				<input type="text" name="keywords" class="form-control" size="27" placeholder="Text for search">
			</div>
			<button type="submit" class="btn btn-default center-block">{$lang.search_title}</button>
		</form>
	</div>
</div>