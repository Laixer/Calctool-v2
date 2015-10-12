<?php

namespace Calctool\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Calctool\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //
$router->filter('admin', function()
{
	if (Auth::guest())
	{
		return \Redirect::guest('login');
	}

	//if(!in_array(UserType::find(Auth::user()->user_type)->user_type, array('admin', 'system'))) {
	if (!Auth::user()->isAdmin())
	{
		//return Redirect::to('/');
		return \Response::view('generic.404', array('url' => Request::path()), 404);
	}

});
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
