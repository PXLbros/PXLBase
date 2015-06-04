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

	public function getDynamicTableHTML()
	{
	}
}