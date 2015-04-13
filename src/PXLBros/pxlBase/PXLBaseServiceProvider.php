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
		$this->app->booting(function ($app)
		{
			$app['modules']->register();
		});
	}
}