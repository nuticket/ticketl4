<?php namespace App\Providers; 

use Illuminate\Support\ServiceProvider;
use App\Services\ValidationRules;
use App\Commands\ConfigCommand;

class AppServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->app['config']->set("orchestra/memory::fluent.default.table", 'config');
		$this->app['orchestra.memory']->setDefaultDriver('fluent.default');

		$this->app['validator']->resolver(function($translator, $data, $rules, $messages)
		{
		    return new ValidationRules($translator, $data, $rules, $messages);
		});
	}

    public function register()
    {
        // require(app_path() . '/app/Support/helpers.php');
        $this->app['command.nut.config'] = $this->app->share(function($app)
		{
			return new ConfigCommand($this->app);
		});
		$this->commands('command.nut.config');
    }

}