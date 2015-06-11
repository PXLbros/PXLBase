<div class="ui compact menu small">
	<div class="ui dropdown item manage-dropdown">
		<i class="dropdown icon"></i>
		Manage

		<div class="menu">
			<a href="<?= $item->dynamicItem->editURL ?>" class="item">Edit</a>
			<a href="<?= $item->dynamicItem->deleteURL ?>" class="item delete-button">Delete</a>
		</div>
	</div>
</div>