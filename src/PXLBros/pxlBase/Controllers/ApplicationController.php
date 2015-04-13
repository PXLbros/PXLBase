<?php namespace PXLBros\pxlBase\Controllers;

class ApplicationController extends CoreController
{
	public function callAction($method, $parameters)
	{
		parent::initLayout($method, $parameters);

		return parent::callAction($method, $parameters);
	}
}