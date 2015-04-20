<?php namespace PXLBros\PXLFramework\Http\Controllers;

class DashboardController extends ApplicationController
{
	public $layout = 'dashboard';

	private $breadcrumb_items = [];

	public function afterLayoutInit()
	{
		$this->initBreadcrumb();

		parent::afterLayoutInit();
	}

	public function beforeDisplay()
	{
		$this->setPageTitleParts('Dashboard');

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