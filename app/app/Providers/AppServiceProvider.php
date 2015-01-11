<?php namespace App\Providers; 

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;

class AppServiceProvider extends ServiceProvider {

	public function boot()
	{
		
	}

    public function register()
    {
        $this->app->bind('foo', function()
        {
            return new Foo;
        });
    }

}