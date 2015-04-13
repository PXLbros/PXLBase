<?= $jquery . $inline_js ?>

<?php foreach ( $assets[\PXLBros\PXLBase\Controllers\CoreController::ASSET_JS] as $js_file ): ?>
	<script src="<?= ($js_file['external'] === FALSE ? $base_url : '') . $js_file['path'] ?>"></script>
<?php endforeach ?>