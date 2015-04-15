<?php namespace PXLBros\PXLFramework\Controllers\Dashboard\Home;

use PXLBros\PXLFramework\Controllers\ApplicationController;

class HomeController extends ApplicationController
{
	public function home()
	{
		return $this->display('Dashboard');
	}
}