<?php namespace PXLBros\PXLFramework;

class PXLFrameworkServiceProvider extends \Illuminate\Support\ServiceProvider
{
	protected $defer = false;

	public function boot()
	{
		$this->publishes(
		[
			__DIR__ . '/../../config/pxl.php' => config_path('pxl.php')
		]);

		$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pxl');

	    /*$this->publishes(
	    [
	        __DIR__ . '/../../resources/views' => base_path('resources/views/vendor/pxlbros/pxlframework'),
	    ]);*/

	    $this->publishes(
	    [
            __DIR__ . '/../../database/migrations/' => database_path('/migrations')
		], 'migrations');

		$this->publishes(
	    [
	        __DIR__ . '/../../resources/assets' => base_path('resources/assets/vendor/pxlbros/pxlframework'),
	    ]);
	}

	public function register()
	{
		$this->mergeConfigFrom
		(
			__DIR__ . '/../../config/pxl.php', 'pxl'
		);
	}

	public function provides()
	{
		return ['pxl'];
	}
}