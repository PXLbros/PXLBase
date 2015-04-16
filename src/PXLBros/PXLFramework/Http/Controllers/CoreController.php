<?php namespace PXLBros\PXLFramework\Http\Controllers;

use PXLBros\PXLFramework\Helpers\Ajax;
use PXLBros\PXLFramework\Helpers\Str;
use PXLBros\PXLFramework\Helpers\UI;

abstract class CoreController extends \Illuminate\Routing\Controller
{
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	const SECTION_ALL = 'all';
	const SECTION_LAYOUT = 'layout';
	const SECTION_CONTENT = 'content';
	const SECTION_JS = 'js';

	const ASSET_CSS = 'css';
	const ASSET_JS = 'js';

	const LIBRARY_LOAD_PRIORITY_PRE = 'pre';
	const LIBRARY_LOAD_PRIORITY_POST = 'post';

	private $layout_view_filename;
	private $layout_view;

	private $js_layout_filename;
	private $js_layout_path;
	private $js_short_auto_path;
	private $js_auto_path;

	private $css_layout_filename;
	private $css_layout_path;
	private $css_short_auto_path;
	private $css_auto_path;

	protected $current_controller = [];
	protected $current_action = [];
	protected $current_page;

	protected $base_url;

	protected $ui;

	protected $is_ajax;
	protected $ajax;

	protected $page_id;

	private $data =
	[
		self::SECTION_LAYOUT => [],
		self::SECTION_CONTENT => [],
		self::SECTION_JS => []
	];

	private $library_view_data = [];

	private $assets =
	[
		self::ASSET_CSS => [],
		self::ASSET_JS => []
	];

	private $libraries = [];
	private $loaded_libraries =
	[
		self::LIBRARY_LOAD_PRIORITY_PRE => [],
		self::LIBRARY_LOAD_PRIORITY_POST => []
	];

	public function afterLayoutInit()
	{
	}

	protected function initLayout($method, $parameters)
	{
		if ( !isset($this->layout) )
		{
			throw new \Exception('No layout view set.');
		}

		$this->layout_view_filename = 'layouts/' . $this->layout;
		$this->layout_view = view($this->layout_view_filename);

		$current_controller = $this->getController();
		$current_controller_original = Str::hyphenToUnderscore($current_controller);

		$this->current_controller =
		[
			'original' => $current_controller_original,
			'underscore' => Str::slashToUnderscore($current_controller_original)
		];

		$this->current_action =
		[
			'original' => $method,
			'underscore' => Str::camelCaseToUnderscore($method),
			'hyphen' => Str::camelCaseToHyphen($method)
		];
		
		$this->js_layout_filename = 'js/' . str_replace('.', '/', $this->layout_view->getName()) . '.js';
		$this->js_layout_path = public_path() . '/' . $this->js_layout_filename;
		$this->js_short_auto_path = 'js/layouts/' . $this->current_controller['original'] . '/' . $this->current_action['underscore'] . '.js';
		$this->js_auto_path = public_path() . '/' . $this->js_short_auto_path;
		
		$this->css_layout_filename = 'css/' . str_replace('.', '/', $this->layout_view->getName()) . '.css';
		$this->css_layout_path = public_path() . '/' . $this->css_layout_filename;
		$this->css_short_auto_path = 'css/layouts/' . $this->current_controller['original'] . '/' . $this->current_action['underscore'] . '.css';
		$this->css_auto_path = public_path() . '/' . $this->css_short_auto_path;

		$this->current_page = $current_controller . '/' . $this->current_action['hyphen'];
		$this->base_url = \URL::route('home', [], false);
		$this->page_id = $this->current_controller['underscore'] . '_' . $this->current_action['underscore'] . '_page';

		$this->assignLibraryViewData('current_page', $this->current_page, self::SECTION_ALL);
		$this->assignLibraryViewData('page_id', $this->page_id, self::SECTION_ALL);
		$this->assignLibraryViewData('base_url', $this->base_url, self::SECTION_ALL);

		$this->ui = new UI();

		/*if ( $this->ui->haveNotification() )
		{
			$this->assign('pxl_notification', $this->ui->getNotification(), 'js');

			$this->ui->deleteNotification();
		}*/

		$this->is_ajax = (\Request::ajax() === true);
		$this->ajax = new Ajax($this->ui);

		// CSRF
		$csrf_token = csrf_token();

		$this->assign('csrf_token', $csrf_token, self::SECTION_CONTENT);
		
		$this->assignLibraryViewData('csrf_token', app('Illuminate\Encryption\Encrypter')->encrypt($csrf_token), self::SECTION_JS);

		$this->assign('debug', env('APP_DEBUG'), self::SECTION_JS);

		$this->afterLayoutInit();
	}

	protected function getController()
	{
		$controller = \Illuminate\Support\Str::parseCallback(\Route::currentRouteAction(), null)[0];

		if ( !preg_match('/^App\\\\Http\\\\Controllers\\\\([a-zA-Z\\\\]+)Controller$/', $controller, $controller_matches) )
		{
			throw new \Exception('Could not parse controller.');
		}

		return strtolower(str_replace('\\', '/', Str::camelCaseToHyphen($controller_matches[1])));
	}

	public function assign($key, $value, $section = self::SECTION_CONTENT)
	{
		$assign = function($section, $key, $value)
		{
            if ( isset($this->data[$section][$key]) )
            {
                throw new \Exception('Key "' . $key . '" already assiged.');
            }

            $this->data[$section][$key] = $value;
		};

		if ( $section === self::SECTION_ALL )
		{
			$section = [self::SECTION_LAYOUT, self::SECTION_CONTENT, self::SECTION_JS];
		}

		if ( is_array($section) )
		{
			$sections = (array)$section;

			foreach ( $sections as $section )
			{
				$assign($section, $key, $value);
			}
		}
		else
		{
			$assign($section, $key, $value);
		}
	}

	private function assignLibraryViewData($key, $value, $section)
    {
        $assign = function($section, $key, $value)
		{
            if ( isset($this->library_view_data[$section][$key]) )
            {
                throw new \Exception('Key "' . $key . '" already assiged.');
            }

            $this->library_view_data[$section][$key] = $value;
		};

		if ( $section === self::SECTION_ALL )
		{
			$section = [self::SECTION_LAYOUT, self::SECTION_CONTENT, self::SECTION_JS];
		}

		if ( is_array($section) )
		{
			$sections = (array)$section;

			foreach ( $sections as $section )
			{
				$assign($section, $key, $value);
			}
		}
		else
		{
			$assign($section, $key, $value);
		}
    }

	protected function addLibrary($library_name, $css_files = null, $js_files = null)
	{
		$this->libraries[$library_name] =
		[
			self::ASSET_CSS => $css_files,
			self::ASSET_JS => $js_files
		];
	}

	protected function loadCSS($path, $external = false)
	{
		if ( \App::environment() === 'local' )
		{
			foreach ( $this->assets[self::ASSET_CSS] as $css_file )
			{
				if ( $css_file['path'] === $path )
				{
					throw new \Exception('Stylesheet "' . $path . '" already added.');
				}
			}
		}

		$this->assets[self::ASSET_CSS][] =
		[
			'path' => $path,
			'external' => $external
		];
	}

	protected function loadJS($path, $external = false)
	{
		if ( \App::environment() === 'local' )
		{
			foreach ( $this->assets[self::ASSET_JS] as $js_file )
			{
				if ( $js_file['path'] === $path )
				{
					throw new \Exception('JavaScript "' . $path . '" already added.');
				}
			}
		}

		$this->assets[self::ASSET_JS][] =
		[
			'path' => $path,
			'external' => $external
		];
	}

	protected function loadLibrary($library_name, $load_priority = self::LIBRARY_LOAD_PRIORITY_PRE)
	{
		if ( !isset($this->libraries[$library_name]) )
		{
			throw new \Exception('Library "' . $library_name . '" does not exist.');
		}

		$this->loaded_libraries[$load_priority][] = $library_name;
	}

	private function includeCSS($css)
	{
		if ( is_array($css) )
		{
			foreach ( $css as $css_file )
			{
				$this->loadCSS($css_file);
			}
		}
		else
		{
			$this->loadCSS($css);
		}
	}
	private function includeJS($js)
	{
		if ( is_array($js) )
		{
			foreach ( $js as $js_file )
			{
				$this->loadJS($js_file);
			}
		}
		else
		{
			$this->loadJS($js);
		}
	}

	private function includeLibrary($library_name)
	{
		if ( !isset($this->libraries[$library_name]) )
		{
			throw new \Exception('Could not find library "' . $library_name . '".');
		}

		$library = $this->libraries[$library_name];

		if ( isset($library[self::ASSET_CSS]) )
		{
			$this->includeCSS($library[self::ASSET_CSS]);
		}

		if ( isset($library[self::ASSET_JS]) )
		{
			$this->includeJS($library[self::ASSET_JS]);
		}
	}

	private function includeAssets($libraries, array $css_files, array $js_files)
	{
		// Pre-loaded libraries
		foreach ( $this->loaded_libraries[self::LIBRARY_LOAD_PRIORITY_PRE] as $lib_name )
		{
			$this->includeLibrary($lib_name);
		}

		// Libraries loaded from display() function
		foreach ( $libraries as $lib_name )
		{
			$this->includeLibrary($lib_name);
		}

		// Post-loaded libraries
		foreach ( $this->loaded_libraries[self::LIBRARY_LOAD_PRIORITY_POST] as $lib_name )
		{
			$this->includeLibrary($lib_name);
		}

		// Auto load layout CSS & JS
		if ( file_exists($this->css_layout_path) )
		{
			$this->loadCSS($this->css_layout_filename);
		}

		//$js_layout_filename = 'js/' . str_replace('.', '/', $this->layout_view->getName()) . '.js';
		//$js_layout_path = public_path() . '/' . $js_layout_filename;

		if ( file_exists($this->js_layout_path) )
		{
			$this->loadJS($this->js_layout_filename);
		}

		// Automatically load CSS & JS based off route
		if ( file_exists($this->css_auto_path) )
		{
			$this->includeCSS($this->css_short_auto_path);
		}

		$js_short_auto_path = 'js/layouts/' . $this->current_controller['original'] . '/' . $this->current_action['underscore'] . '.js';
		$js_auto_path = public_path() . '/' . $js_short_auto_path;

		if ( file_exists($this->js_auto_path) )
		{
			$this->includeJS($this->js_short_auto_path);
		}

		// CSS loaded from display() function
		foreach ( $css_files as $css_file )
		{
			$this->includeCSS($css_file);
		}

		// JS loaded from display() function
		foreach ( $js_files as $js_file )
		{
			$this->includeJS($js_file);
		}
	}

	private function includeContentData()
	{
		foreach ( $this->data[self::SECTION_LAYOUT] as $key => $value )
		{
			$this->layout_view->$key = $value;
		}
	}

	private function includeLibraryViewData()
	{
	    foreach ( $this->library_view_data[self::SECTION_LAYOUT] as $key => $value )
        {
            $this->layout_view->pxl[$key] = $value;
        }

        $this->assign('pxl', $this->library_view_data[self::SECTION_JS], self::SECTION_JS);
	}

	private function getContentData()
	{
		return array_merge
		(
			$this->data[self::SECTION_CONTENT],
			[
			    'pxl' => $this->library_view_data[self::SECTION_CONTENT],
			    'js_vars' => $this->data[self::SECTION_JS]
			]
		);
	}

	private function generatePageTitle($page_title, $page_title_suffix)
	{
		if ( $page_title !== null )
		{
			return ((is_array($page_title) ? implode(' | ', $page_title) : $page_title) . ($page_title_suffix ? ' ' . \Config::get('pxl.page_title_separator') . ' ' . \Config::get('pxl.page_title_suffix') : ''));
		}
		else
		{
			return \Config::get('pxl.default_page_title');
		}
	}

	private function assignCSS()
	{
		$css_html_view = view('pxl::layouts.partials.css_includes');
		$css_html_view->base_url = $this->base_url;
		$css_html_view->css_files = $this->assets[self::ASSET_CSS];

		$this->layout_view->pxl['css_includes'] = $css_html_view->render();
	}

	private function assignInlineJS()
	{
		$inline_js_view = view('pxl::layouts.partials.inline_js');
		$inline_js_view->js_variables = $this->data[self::ASSET_JS];

		$this->layout_view->pxl['inline_js'] = $inline_js_view->render();
	}

	private function assignJS()
	{
		$js_html_view = view('pxl::layouts.partials.js_includes');
		$js_html_view->base_url = $this->base_url;
		$js_html_view->js_files = $this->assets[self::ASSET_JS];

		$this->layout_view->pxl['js_includes'] = $js_html_view->render();
	}

	public function beforeDisplay()
	{
	}

	public function display($page_title = null, $page_title_suffix = true, array $libraries = [], array $css_files = [], array $js_files = [], $view_file = null)
	{
		$this->beforeDisplay();

		$this->assignLibraryViewData('page_title', $this->generatePageTitle($page_title, $page_title_suffix), self::SECTION_LAYOUT);

		$this->includeAssets($libraries, $css_files, $js_files);

		$this->includeLibraryViewData();
		$this->includeContentData();

		$this->assignCSS();
		$this->assignInlineJS();
		$this->assignJS();

		if ( $view_file === null )
		{
			$view_file = 'layouts.' . $this->current_controller['original'] . '.' . $this->current_action['underscore'];
		}

		if ( \Config::get('pxl.show_debug_info') === true && !\App::environment('production') )
		{
			$debug_info_view = view('pxl::layouts.partials.debug_info');
			$debug_info_view->current_controller = print_r($this->current_controller, true);
			$debug_info_view->current_action = print_r($this->current_action, true);
			$debug_info_view->current_page = $this->current_page;
			$debug_info_view->js_layout_path = $this->js_layout_path;
			$debug_info_view->js_auto_path = $this->js_auto_path;
			$debug_info_view->css_layout_path = $this->css_layout_path;
			$debug_info_view->css_auto_path = $this->css_auto_path;
			$debug_info_view->css_files = print_r($this->assets[self::ASSET_CSS], true);
			$debug_info_view->js_files = print_r($this->assets[self::ASSET_JS], true);
			$debug_info_view->inline_js_variables = print_r($this->data[self::ASSET_JS], true);
			$debug_info_view->loaded_libraries = print_r($this->loaded_libraries, true);

			return $debug_info_view->render();
		}
		else
		{
			return $this->layout_view->nest
			(
				'content',
				$view_file,
				$this->getContentData()
			);
		}
	}

	public function getCurrentController()
	{
		return $this->current_controller['original'];
	}

	public function getCurrentAction()
	{
		return $this->current_action['original'];
	}
}