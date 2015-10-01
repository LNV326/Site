{* Smarty *}
{* Правое меню: Случайный скриншот *}
{* Refactored in 2015 *}

<div class="sideblock-block">
	<div class="sideblock-header">Случайный скриншот</div>
	<div class="sideblock-body">
		<figure id='block-rndscreen'>
			<a href="http://images.nfsko.ru/image.php?gallery=view&amp;image={$image.id}" rel="nofollow" target="_blank">
				<img src="http://images.{$conf.site_url}/gallery/{$subcat_row.dir_name}/thumbs/{$image.filename}" {$size_px[3]} alt="Увеличить" class='img-rounded'/>
			</a>
			<figcaption><a href="http://images.nfsko.ru/index.php?page=gallery&amp;view={$subcat_row.id}" rel="nofollow">{$lang.gallery_link}</a></figcaption>
		</figure>
	</div>
</div>