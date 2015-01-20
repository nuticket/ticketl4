<?php namespace App\Providers; 

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class AppServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->app['config']->set("orchestra/memory::fluent.default.table", 'config');
		$this->app['orchestra.memory']->setDefaultDriver('fluent.config');
	}

    public function register()
    {
        // require(app_path() . '/app/Support/helpers.php');
    }

}