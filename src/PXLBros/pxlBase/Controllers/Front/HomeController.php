<?php namespace PXLBros\pxlBase\Controllers\Front;

class HomeController extends FrontController
{
	public function home()
	{
		return $this->display('Home');
	}
}