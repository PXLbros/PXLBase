<?php namespace PXLBros\PXLFramework;

class PXLFrameworkServiceProvider extends \Illuminate\Support\ServiceProvider
{
	protected $defer = false;

	public function boot()
	{
		$this->publishes(
		[
			__DIR__ . '/../../config/pxlframework.php' => config_path('pxlframework.php')
		]);

		$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pxlframework');

	    $this->publishes(
	    [
	        __DIR__ . '/../../resources/views' => base_path('resources/views/vendor/pxlbros/pxlframework'),
	    ]);
	}

	public function register()
	{
		$this->mergeConfigFrom
		(
			__DIR__ . '/../../config/pxlframework.php', 'pxlframework'
		);
	}

	public function provides()
	{
		return ['pxlframework'];
	}
}