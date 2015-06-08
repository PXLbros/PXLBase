<?php namespace PXLBros\PXLFramework\Helpers;

trait DynamicItem
{
	private static $dynamic_item_config =
	[
		'model' => null,
		'identifier' => null,
		'table' =>
		[
			'routes' =>
			[
				'pages' => [],
				'get' => null,
				'add' => null
			],
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
		self::$dynamic_item_config = array_replace_recursive(self::$dynamic_item_config, $config);
	}

	public function initDynamicItem()
	{
		if ( in_array(\URL::current(), self::$dynamic_item_config['table']['routes']['pages']) )
		{
			$dynamic_table_view = view('pxl::layouts/partials/dynamic_item/table/table_container');
			$dynamic_table_view->add_url = self::$dynamic_item_config['table']['routes']['add'];
			$dynamic_table_view->identifier = self::$dynamic_item_config['identifier'];

			$this->assign('dynamic_table', $dynamic_table_view->render());
		}

		$this->assign('dynamic_item', [ 'config' => self::$dynamic_item_config ], self::SECTION_JS);
	}

	public function getDynamicTableHTML()
	{
		$called_class = get_called_class();

		if ( !method_exists($called_class, 'getDynamicTableData') )
		{
			throw new \Exception($called_class . ' is missing required dynamic table function "getDynamicTableData."');
		}

		if ( !method_exists($called_class, 'setDynamicTableColumnData') )
		{
			throw new \Exception($called_class . ' is missing required dynamic table function "setDynamicTableColumnData".');
		}

		$default_sort_column = $this->getTableColumn(self::$dynamic_item_config['table']['default_sorting']['column']);

		$sort_column = \Input::get('sort_column', $default_sort_column['sort']);
		$sort_order = \Input::get('sort_order', self::$dynamic_item_config['table']['default_sorting']['order']);

		$filters =
		[
			'search_query' => trim(\Input::get('search_query'))
		];

		$items = $this->getDynamicTableData($filters);

		$items = $items->orderBy($sort_column, $sort_order);

		if ( self::$dynamic_item_config['table']['paging']['enabled'] === true )
		{
			$items = $items->paginate(self::$dynamic_item_config['table']['paging']['num_per_page']);
		}
		else
		{
			$items = $items->get();
		}

		$dynamic_table_column_data_result = $this->setDynamicTableColumnData($items);

		if ( ($dynamic_table_column_data_result === NULL) || (!isset($dynamic_table_column_data_result['items'], $dynamic_table_column_data_result['table_column_data'])) || (!is_array($dynamic_table_column_data_result['items']) && !$dynamic_table_column_data_result['items'] instanceof \Illuminate\Pagination\LengthAwarePaginator) )
		{
			throw new \Exception($called_class . ' didn\'t intialize setDynamicTableColumnData correctly.');
		}

		$items = $dynamic_table_column_data_result['items'];
		$table_column_data = $dynamic_table_column_data_result['table_column_data'];

		$table_view = view('pxl::layouts/partials/dynamic_item/table/table');

		$model = self::$dynamic_item_config['model'];

		$table_view->num_total_items = $model::all()->count();

		if ( self::$dynamic_item_config['table']['paging'] )
		{
			$table_view->num_total_filtered_items = $items->total();
			$table_view->num_page_items = count($items);

			$this->ajax->assign('paging', ['current_page' => $items->currentPage(), 'num_pages' => $items->lastPage()]);
		}
		else
		{
			$num_items = $items->count();

			$table_view->num_total_filtered_items = $num_items;
			$table_view->num_page_items = $num_items;
		}

		$table_view->identifier = self::$dynamic_item_config['identifier'];
		$table_view->table_columns = self::$dynamic_item_config['table']['columns'];
		$table_view->table_column_data = $table_column_data;
		$table_view->num_table_columns = count(self::$dynamic_item_config['table']['columns']);
		$table_view->items = $items;
		$table_view->filters = $filters;
		$table_view->search_enabled = self::$dynamic_item_config['table']['search']['enabled'];
		$table_view->paging_enabled = self::$dynamic_item_config['table']['paging']['enabled'];

		$this->ajax->assign('html', $table_view->render());

		return $this->ajax->output();
	}

	private function getTableColumn($table_column_id)
	{
		return (isset(self::$dynamic_item_config['table']['columns'][$table_column_id]) ? self::$dynamic_item_config['table']['columns'][$table_column_id] : null);
	}

	public static function convertTableColumnSize($size)
	{
		switch ( $size )
		{
			case 1: return 'one';
			case 2: return 'two';
			case 3: return 'three';
			case 4: return 'four';
			case 5: return 'five';
			case 6: return 'six';
			case 7: return 'seven';
			case 8: return 'eight';
			case 9: return 'nine';
			case 10: return 'ten';
			case 11: return 'eleven';
			case 12: return 'twelwe';
			case 13: return 'thirteen';
			case 14: return 'fourteen';
			case 15: return 'fifteen';
			case 16: return 'sixteen';
		}
	}

	public function dynamicItem($id = NULL)
	{
		$called_class = get_called_class();

		$model = self::$dynamic_item_config['model'];

		if ( $id !== NULL )
		{
			$title_column = self::$dynamic_item_config['title_column'];

			$item_to_edit = $model::find('id', $id);

			if ( $item_to_edit === null )
			{
				$this->ui->showWarning(sprintf(self::$dynamic_item_config['item']['save']['item_not_found']['message'], $id));

				return \Redirect::to(self::$dynamic_item_config['item']['save']['item_not_found']['redirect']);
			}
		}
		else
		{
			$item_to_edit = NULL;
		}

		$this->assign('item_to_edit', $item_to_edit);
		$this->assign('item_id_to_edit', ($item_to_edit !== NULL ? $item_to_edit->id : NULL), self::SECTION_JS);

		$this->assign('title_column', self::$dynamic_item_config['item']['title_column']);
		$this->assign('identifier', self::$dynamic_item_config['identifier']);

		return $this->display([($item_to_edit !== NULL ? e($item_to_edit->$title_column) : 'Add'), ucfirst(str_plural(self::$dynamic_item_config['identifier']))], false, 'pxl::layouts/partials/dynamic_item/item');
	}
}