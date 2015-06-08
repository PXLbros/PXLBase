<h1><?php if ( $item_to_edit !== NULL ): ?><?= e($item_to_edit->$title_column) ?><?php else: ?>Add <?= ucfirst($identifier) ?><?php endif ?></h1>

<form action="<?= URL::current() ?>" method="post" id="dynamic-item-form"<?php if ( $have_file_upload_field === true ): ?> enctype="multipart/form-data"<?php endif ?> class="ui form segment">
	<div id="dynamic-item-form-loader" class="ui inverted dimmer">
    	<div class="ui text loader"></div>
  	</div>

  	<?php if ( isset($tabs) ): ?>
		<div id="dynamic_tabs_container">
			<div id="dynamic_item_tabs" class="ui top attached tabular menu">
				<?php foreach ( $tabs as $tab_id => $tab_data ): ?>
					<?php
					$tab_locked = ($item_to_edit === null && $tab_data['only_edit'] === DYNAMIC_ITEM_ONLY_EDIT_DISABLE);
					?>

					<a href="javascript:" data-tab="<?= $tab_id ?>" class="item<?php if ( $tab_id === 'general' ): ?> active<?php endif ?><?php if ( $tab_locked === true ): ?> popup locked<?php endif ?>"<?php if ( $tab_locked === TRUE ): ?> data-content="Save <?= $identifier['singular'] ?> before using this section" data-variation="inverted"<?php endif ?>><?= $tab_data['text'] ?></a>
				<?php endforeach ?>
			</div>

			<?php foreach ( $tabs as $tab_id => $tab_data ): ?>
				<?php
				$tab_locked = ($item_to_edit === null && $tab_data['only_edit'] === DYNAMIC_ITEM_ONLY_EDIT_DISABLE);
				?>

				<div data-tab="<?= $tab_id ?>" class="ui bottom attached tab segment<?php if ( $tab_id === 'general' ): ?> active<?php endif ?><?php if ( $tab_locked === true ): ?> locked<?php endif ?>">
					<?php if ( $tab_locked ): ?>
						<div class="locked-tab-info">
							<span>Save <?= $identifier['singular'] ?> before using this section.</span>
						</div>

						<div class="locked-tab-content">
							<?= $tab_data['html'] ?>
						</div>
					<?php else: ?>
						<?= $tab_data['html'] ?>
					<?php endif ?>

					<?php if ( isset($tab_data['active_toggle']) && $tab_data['active_toggle'] === true ): ?>
						<?= $active_toggle ?>
					<?php endif ?>

					<?php if ( isset($tab_data['save_button']) && $tab_data['save_button'] === true ): ?>
						<?= $save_button_html ?>
					<?php endif ?>
				</div>
			<?php endforeach ?>
		</div>
	<?php else: ?>
		<?php foreach ( $fields as $field ): ?>
			<?= $field ?>
		<?php endforeach ?>
	<?php endif ?>

	<?php if ( !isset($tabs) ): ?>
		<?php if ( isset($active_toggle) ): ?>
			<?= $active_toggle ?>
		<?php endif ?>

		<?= $save_button_html ?>
	<?php endif ?>
</form>