<?php namespace PXLBros\PXLFramework\Helpers;

define('DYNAMIC_ITEM_ALWAYS_REQUIRED', 1);
define('DYNAMIC_ITEM_REQUIRED_ON_ADD', 2);

trait DynamicItem
{
	private static $column_types_to_trim = [ 'text', 'textarea', 'email' ];

	private static $column_types_with_custom_saving = [ 'image' ];

	private static $dynamic_item_config =
	[
		'model' => null,
		'identifier' => null,
		'columns' => [],
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
			'routes' =>
			[
				'add' => null,
				'added_redirect' => null,
				'edited_redirect' => null
			],
			'title_column' => null,
			'slug_column' => null,
			'fields' => null,
			'tabs' => null
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
			$dynamic_table_view->add_url = self::$dynamic_item_config['item']['routes']['add'];
			$dynamic_table_view->identifier = self::$dynamic_item_config['identifier'];

			$this->assign('dynamic_table', $dynamic_table_view->render());

			$this->assign('dynamic_item', [ 'config' => self::$dynamic_item_config, 'current_page' => 'table' ], self::SECTION_JS);
		}
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
			$title_column = self::$dynamic_item_config['item']['title_column'];

			$item_to_edit = $model::find($id);

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

		$this->assign('title_column', self::$dynamic_item_config['item']['title_column']);
		$this->assign('identifier', self::$dynamic_item_config['identifier']);

		$have_file_upload_field = false;

		if ( is_array(self::$dynamic_item_config['item']['tabs']) )
		{
		}
		elseif ( is_array(self::$dynamic_item_config['item']['fields']) )
		{
			$fields = [];

			foreach ( self::$dynamic_item_config['item']['fields'] as $field_id => $field_data )
			{
				$column_data = self::$dynamic_item_config['columns'][$field_id];

				if ( $column_data['form']['type'] === 'file' )
				{
					$have_file_upload_field = true;
				}

				$fields[] = self::getFormFieldHTML($field_id, $item_to_edit);
			}
		}

		$this->assign('fields', $fields);
		$this->assign('save_button_html', '<button type="submit" id="dynamic-item-save-button" class="ui submit button">' . ($item_to_edit !== NULL ? 'Save' : 'Add') . '</button>');
		$this->assign('have_file_upload_field', $have_file_upload_field);

		$dynamic_item_js =
		[
			'DYNAMIC_ITEM_ALWAYS_REQUIRED' => DYNAMIC_ITEM_ALWAYS_REQUIRED,
			'DYNAMIC_ITEM_REQUIRED_ON_ADD' => DYNAMIC_ITEM_REQUIRED_ON_ADD,
			'config' => self::$dynamic_item_config,
			'columns' => self::$dynamic_item_config['columns'],
			'current_page' => 'item',
			'item_id_to_edit' => ($item_to_edit !== null ? $item_to_edit->id : null)
		];

		$this->assign('dynamic_item', $dynamic_item_js, self::SECTION_JS);

		return $this->display(($item_to_edit !== NULL ? e($item_to_edit->$title_column) : 'Add ' . ucfirst(self::$dynamic_item_config['identifier'])), false, 'pxl::layouts/partials/dynamic_item/item/item');
	}

	private static function getFormFieldHTML($column_id, $item_to_edit, $params = null)
	{
		$column_data = self::$dynamic_item_config['columns'][$column_id];

		$form_field_view = view('pxl::layouts/partials/dynamic_item/item/fields/' . $column_data['form']['type']);
		$form_field_view->column_id = $column_id;
		$form_field_view->column_data = $column_data;
		$form_field_view->item_to_edit = $item_to_edit;
		$form_field_view->params = $params;

		if ( isset($params['options']) )
		{
			$form_field_view->num_options = count($params['options']);
			$form_field_view->options = $params['options'];

			if ( $column_data['form']['type'] === 'select' && isset($params['selected_option']) )
			{
				$form_field_view->selected_option = $params['selected_option'];
			}
			elseif ( $column_data['form']['type'] === 'checkbox' && isset($params['selected_options']) )
			{
				$form_field_view->selected_options = $params['selected_options'];
			}
		}

		return $form_field_view->render();
	}

	public function saveDynamicItem($id = null)
	{
		$called_class = get_called_class();

		$input = \Input::all();

		$have_custom_saving_columns = ($input['have_custom_saving_columns'] === 'yes');

		$model = self::$dynamic_item_config['model'];
		$dynamic_item_title_column = self::$dynamic_item_config['item']['title_column'];

		if ( $id !== NULL )
		{
			try
			{
				$item_to_edit = $model::find($id);

				if ( method_exists($called_class, 'dynamicItemPreEdit') )
				{
					self::dynamicItemPreEdit($item_to_edit);
				}

				// Delete
				/*foreach ( $items_to_delete as $item_to_delete_column_id )
				{
					$this->deleteDynamicItemColumn($item_to_edit, $item_to_delete_column_id);
				}*/

				// Edit
				$edited_item_data = self::editDynamicItem($item_to_edit, $input);

				if ( method_exists($called_class, 'dynamicItemPostEdit') )
				{
					$this->dynamicItemPostEdit($item_to_edit);
				}

				//$success_message = sprintf($this->dynamic_item_options['save']['edit']['success']['message'], $item_to_edit->$dynamic_item_title_column);

				if ( $have_custom_saving_columns === true )
				{
					//$this->ui->showSuccess($success_message);
				}
				else
				{
					//$this->ajax->showSuccess($success_message);

					return $this->ajax->redirect(($edited_item_data['redirect'] !== null ? $edited_item_data['redirect'] : ''), 750);
				}
			}
			catch ( \Illuminate\Database\Eloquent\ModelNotFoundException $e )
			{
				//$this->ajax->showWarning(sprintf(self::$dynamic_item_config['item_not_found']['message'], $id));

				return $this->ajax->output();
			}
		}
		else
		{
			$added_item_data = self::addDynamicItem($input);

			$this->ajax->assign('added_item_id', $added_item_data['added_item']->id);

			//$success_message = sprintf($this->dynamic_item_options['save']['add']['success']['message'], $added_item->$dynamic_item_title_column);

			if ( $have_custom_saving_columns === TRUE )
			{
				//$this->ui->showSuccess($success_message);
			}
			else
			{
				//$this->ajax->showSuccess($success_message);

				return $this->ajax->redirect(($added_item_data['redirect'] !== null ? $added_item_data['redirect'] : ''), 750);
			}
		}

		return $this->ajax->output();
	}

	private static function addDynamicItem($input)
	{
		$save_data = self::assignSaveData($input);

		$item = new self::$dynamic_item_config['model'];

		foreach ( self::$dynamic_item_config['columns'] as $column_id => $column_data )
		{
			if ( in_array($column_data['form']['type'], self::$column_types_with_custom_saving) || empty($column_data['column']) )
			{
				continue;
			}

			$item->$column_data['column'] = $save_data[$column_id];
		}

		if ( self::$dynamic_item_config['item']['slug_column'] !== NULL )
		{
			$item->slug = $save_data['slug'];
		}

		$item->save();

		if ( method_exists(get_called_class(), 'postAdd') )
		{
			$post_add_data = self::postAdd($item, $save_data);
		}

		return
		[
			'added_item' => $item,
			'redirect' => (isset($post_add_data['redirect']) ? $post_add_data['redirect'] : self::$dynamic_item_config['item']['routes']['added_redirect'])
		];
	}

	private static function editDynamicItem($item_to_edit, $input)
	{
		$save_data = self::assignSaveData($input);

		foreach ( self::$dynamic_item_config['columns'] as $column_id => $column_data )
		{
			if ( in_array($column_data['form']['type'], self::$column_types_with_custom_saving) || empty($column_data['column']) )
			{
				continue;
			}

			$item_to_edit->$column_data['column'] = $save_data[$column_id];
		}

		if ( self::$dynamic_item_config['item']['slug_column'] !== null )
		{
			$item_to_edit->slug = $save_data['slug'];
		}

		$item_to_edit->save();

		if ( method_exists(get_called_class(), 'postEdit') )
		{
			$post_edit_data = $item_to_edit->postEdit($save_data);
		}

		return
		[
			'edited_item' => $item_to_edit,
			'redirect' => (isset($post_edit_data['redirect']) ? $post_edit_data['redirect'] : self::$dynamic_item_config['item']['routes']['edited_redirect'])
		];
	}

	private static function assignSaveData($input)
	{
		$save_data = [];

		foreach ( $input as $input_key => $input_value )
		{
			if ( !isset(self::$dynamic_item_config['columns'][$input_key]) )
			{
				continue;
			}

			$column_data = self::$dynamic_item_config['columns'][$input_key];

			if ( (isset($column_data['nullable']) && $column_data['nullable'] === true) && empty($input_value) )
			{
				$input_value = null;
			}
			elseif ( in_array($column_data['form']['type'], self::$column_types_to_trim) )
			{
				$input_value = trim($input_value);
			}
			elseif ( $column_data['form']['type'] === 'password' )
			{
				$input_value = bcrypt($input_value);
			}

			if ( !in_array($column_data['form']['type'], self::$column_types_with_custom_saving) )
			{
				$save_data[$input_key] = $input_value;
			}
		}

		$called_class = get_called_class();

		if ( self::$dynamic_item_config['item']['slug_column'] !== null )
		{
			if ( !isset($save_data[self::$dynamic_item_config['item']['slug_column']]) )
			{
				throw new \Exception($called_class . ' is trying to slugify column "' . self::$dynamic_item_config['item']['slug_column'] . '" which is not defined.');
			}

			$save_data['slug'] = str_slug(self::$dynamic_item_config['item']['slug_column']);
		}

		if ( method_exists($called_class, 'postAssignSaveData') )
		{
			$save_data = self::postAssignSaveData($save_data, $input);
		}

		return $save_data;
	}
}