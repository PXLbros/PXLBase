<?php foreach ( $js_files as $js_file ): ?>
	<?php if ( $js_file['delayed'] === false ): ?>
		<script src="<?= ($js_file['external'] === FALSE ? $base_url : '') . $js_file['path'] ?>"></script>
	<?php endif ?>
<?php endforeach ?>

<?php foreach ( $js_files as $js_file ): ?>
	<?php if ( $js_file['delayed'] === true ): ?>
		<script src="<?= ($js_file['external'] === FALSE ? $base_url : '') . $js_file['path'] ?>"></script>
	<?php endif ?>
<?php endforeach ?>