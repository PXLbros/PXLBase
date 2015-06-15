<div class="ui compact menu small">
	<div class="ui dropdown item manage-dropdown">
		<i class="dropdown icon"></i>
		Manage

		<div class="menu">
			<a href="<?= $item->manageDropdown['edit_url'] ?>" class="item">Edit</a>
			<a href="<?= $item->manageDropdown['delete_url'] ?>" class="item delete-button">Delete</a>
		</div>
	</div>
</div>