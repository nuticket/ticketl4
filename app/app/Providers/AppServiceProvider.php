<?php namespace App\Providers; 

use Illuminate\Support\ServiceProvider;
use App\Services\ValidationRules;

class AppServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->app['config']->set("orchestra/memory::fluent.default.table", 'config');
		$this->app['orchestra.memory']->setDefaultDriver('fluent.config');

		$this->app['validator']->resolver(function($translator, $data, $rules, $messages)
		{
		    return new ValidationRules($translator, $data, $rules, $messages);
		});
	}

    public function register()
    {
        // require(app_path() . '/app/Support/helpers.php');
    }

}