<?php if ( $add_url !== null ): ?>
	<a href="<?= $add_url ?>" id="dynamic-table-toolbar" class="ui button tiny">
		Add <?= ucfirst($identifier) ?>
	</a>
<?php endif ?>

<div id="dynamic-table-container">
	<p>Loading <?= str_plural($identifier) ?>...</p>
</div>