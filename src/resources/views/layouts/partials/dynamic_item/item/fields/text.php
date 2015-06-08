<div class="field<?php if ( isset($column_data['form']['validation']['required']) && ($column_data['form']['validation']['required'] === DYNAMIC_ITEM_ALWAYS_REQUIRED || $item_to_edit === NULL && $column_data['form']['validation']['required'] === DYNAMIC_ITEM_REQUIRED_ON_ADD) ): ?> required<?php endif ?>">
	<label for="<?= $column_id ?>"><?= $column_data['title'] ?></label>
	<input type="text" name="<?= $column_data['column'] ?>" id="<?= $column_id ?>"<?php if ( $item_to_edit !== NULL ): ?> value="<?= e($item_to_edit->$column_data['column']) ?>"<?php endif ?>>
</div>