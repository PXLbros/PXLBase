<?php foreach ( $css_files as $css_file ): ?>
	<?php if ( $css_file['delayed'] === false ): ?>
		<link href="<?= ($css_file['external'] === FALSE ? $base_url : '') . $css_file['path'] ?>" rel="stylesheet">
	<?php endif ?>
<?php endforeach ?>

<?php foreach ( $css_files as $css_file ): ?>
	<?php if ( $css_file['delayed'] === true ): ?>
		<link href="<?= ($css_file['external'] === FALSE ? $base_url : '') . $css_file['path'] ?>" rel="stylesheet">
	<?php endif ?>
<?php endforeach ?>