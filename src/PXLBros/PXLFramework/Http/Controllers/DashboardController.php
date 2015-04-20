<?php namespace PXLBros\PXLFramework\Http\Controllers;

class DashboardController extends ApplicationController
{
	public $layout = 'dashboard';

	private $breadcrumb_items = [];

	protected $sign_in_route = 'App\Http\Controllers\Dashboard\AuthController@signIn';

	function __construct()
	{
		$this->beforeFilter('@filterRequests', ['on' => 'get']);
	}

	public function filterRequests($route, $request)
	{
		$user = \Auth::user();

		if ( $user === NULL && $route->getActionName() !== $this->sign_in_route )
		{
			return \Redirect::route('sign-in');
		}
	}

	public function afterLayoutInit()
	{
		$this->initMenu();
		$this->initBreadcrumb();

		parent::afterLayoutInit();
	}

	public function beforeDisplay()
	{
		$breadcrumb_view = view('pxl::layouts/dashboard/partials/breadcrumb');
		$breadcrumb_view->breadcrumb_items = $this->breadcrumb_items;
		$breadcrumb_view->num_breadcrumb_items = count($this->breadcrumb_items);

		$this->assign('breadcrumb', $breadcrumb_view->render(), self::SECTION_LAYOUT);

		parent::beforeDisplay();
	}

	private function initMenu()
	{
		$menu_items =
		[
			[
				'text' => 'Dashboard',
				'icon' => 'dashboard',
				'link' => \URL::route('dashboard'),
				'pages' => ['dashboard/home/home']
			],
			[
				'text' => 'Users',
				'icon' => 'user',
				'link' => \URL::route('dashboard/users'),
				'pages' => ['dashboard/users/users', 'dashboard/users/user']
			]
		];

		$this->assign('menu_items', $menu_items, self::SECTION_LAYOUT);
	}

	private function initBreadcrumb()
	{
		if ( $this->current_page !== 'dashboard/home/home' )
		{
			$this->addBreadcrumbItem('Dashboard', \URL::route('dashboard'));
		}
	}

	protected function addBreadcrumbItem($text, $link = NULL)
	{
		$this->breadcrumb_items[] =
		[
			'text' => $text,
			'link' => $link
		];
	}
}