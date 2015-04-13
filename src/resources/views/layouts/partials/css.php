<?php foreach ( $assets[\PXLBros\PXLBase\Controllers\CoreController::ASSET_CSS] as $css_file ): ?>
	<link href="<?= ($css_file['external'] === FALSE ? $base_url : '') . $css_file['path'] ?>" rel="stylesheet">
<?php endforeach ?>