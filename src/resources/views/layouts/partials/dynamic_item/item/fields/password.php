<div class="field<?php if ( isset($column_data['form']['validation']['required']) && ($column_data['form']['validation']['required'] === DYNAMIC_ITEM_ALWAYS_REQUIRED || $item_to_edit === NULL && $column_data['form']['validation']['required'] === DYNAMIC_ITEM_REQUIRED_ON_ADD) ): ?> required<?php endif ?>">
	<label for="<?= $column_id ?>"><?= $column_data['title'] ?></label>
	<input type="password" name="<?= $column_data['column'] ?>" id="<?= $column_id ?>">
</div>

<?php if ( isset($column_data['form']['verify']) && $column_data['form']['verify'] === true ): ?>
	<div class="field<?php if ( isset($column_data['form']['validation']['required']) && ($column_data['form']['validation']['required'] === DYNAMIC_ITEM_ALWAYS_REQUIRED || $item_to_edit === NULL && $column_data['form']['validation']['required'] === DYNAMIC_ITEM_REQUIRED_ON_ADD) ): ?> required<?php endif ?>">
		<label for="<?= $column_id ?>">Verify <?= $column_data['title'] ?></label>
		<input type="password" name="verify_<?= $column_data['column'] ?>" id="verify-<?= $column_id ?>">
	</div>
<?php endif ?>