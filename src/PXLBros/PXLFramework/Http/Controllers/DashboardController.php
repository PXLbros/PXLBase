<?php namespace PXLBros\PXLFramework\Http\Controllers;

class DashboardController extends ApplicationController
{
	public $layout = 'dashboard';

	private $breadcrumb_items = [];

	protected $home_route;

	public function afterLayoutInit()
	{
		$this->setPageTitleParts('Dashboard');

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

	private function initBreadcrumb()
	{
		if ( $this->current_page !== 'dashboard/home/home' )
		{
			$this->addBreadcrumbItem('Dashboard', ($this->home_route !== null ? $this->home_route : route('dashboard')));
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