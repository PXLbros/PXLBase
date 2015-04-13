<?php namespace PXLBros\PXLBase;

class PXLBaseServiceProvider extends \Illuminate\Support\ServiceProvider
{
	protected $defer = false;

	public function boot()
	{
		$this->publishes(
		[
			__DIR__ . '/../../config/pxlbase.php' => config_path('pxlbase.php')
		]);

		$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pxlbros');

	    $this->publishes(
	    [
	        __DIR__ . '/../../resources/views' => base_path('resources/views/vendor/pxlbros/pxlbase'),
	    ]);
	}

	public function register()
	{
		$this->mergeConfigFrom
		(
			__DIR__ . '/../../config/pxlbase.php', 'pxlbase'
		);

		$this->registerServices();
	}

	public function provides()
	{
		return ['pxlbase'];
	}

	protected function registerServices()
	{
		$this->app->booting(function($app)
		{
			$app['pxlbase']->register();
		});
	}
}