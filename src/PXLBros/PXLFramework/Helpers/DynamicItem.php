<?php namespace PXLBros\PXLFramework\Helpers;

trait DynamicItem
{
	private static $dynamic_item_config =
	[
		'model' => null,
		'identifier' =>
		[
			'singular' => null,
			'plural' => null
		],
		'table' =>
		[
			'route' => null,
			'columns' => [],
			'default_sorting' =>
			[
				'column' => null,
				'order' => 'asc'
			],
			'search' =>
			[
				'enabled' => true,
				'columns' => []
			],
			'paging' =>
			[
				'enabled' => true,
				'num_per_page' => 10
			]
		],
		'item' =>
		[
			'title_column' => null
		]
	];

	public static function configureDynamicItem(array $config)
	{
		self::$dynamic_item_config = array_merge(self::$dynamic_item_config, $config);
	}

	public function initDynamicItem()
	{
		if ( \URL::current() === self::$dynamic_item_config['table']['route'] )
		{
			$dynamic_table_view = view('pxl::layouts/partials/dynamic_item/dynamic_table/dynamic_table_container');
			//$dynamic_table_view->add_link = $this->dynamic_table_options['urls']['add'];
			$dynamic_table_view->identifier = self::$dynamic_item_config['identifier'];

			$this->assign('dynamic_item', '1'/*$dynamic_table_view->render()*/);
			$this->assign('dynamic_item', self::$dynamic_item_config, self::SECTION_JS);
		}
	}

	public function getDynamicTableHTML()
	{
	}
}