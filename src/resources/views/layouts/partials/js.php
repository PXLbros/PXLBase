<?= $jquery . $inline_js ?>

<?php foreach ( $js_files as $js_file ): ?>
	<script src="<?= ($js_file['external'] === FALSE ? $base_url : '') . $js_file['path'] ?>"></script>
<?php endforeach ?>