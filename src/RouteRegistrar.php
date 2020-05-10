<?php


namespace Tools4Schools\MultiTenant;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{

    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->forTenantSelection();
        $this->forChangeTenant();
        //$this->updatePassportTokenRoute();
    }

    /**
     * Register the routes needed for choosing what tenant to use.
     *
     * @return void
     */
    public function forTenantSelection()
    {
        $this->router->group([
            'namespace' => '\Tools4Schools\MultiTenant\Http\Controllers',
            'prefix'=>'tenant',
            'middleware' => ['web', 'auth']
        ], function ($router) {
            $router->get('/', [

                'uses' => 'TenantController@index',
                'as' => 'tenant.selection',
            ]);
        });
    }

    /**
     * Register the routes needed for changing what tenant to use.
     *
     * @return void
     */
    public function forChangeTenant()
    {
        $this->router->group([
            'namespace' => '\Tools4Schools\MultiTenant\Http\Controllers',
            'prefix'=>'tenant',
            'middleware' => ['web', 'auth']
        ], function ($router) {

            $router->post('/', [
                'uses' => 'TenantController@select',
                'as' => 'tenant.select',
            ]);
        });
    }


    public function updatePassportTokenRoute()
    {
        $routeCollection = $this->router->getRoutes();
        $route = $routeCollection->getByName('passport.token');
        $route->action['middleware'] = ['throttle','tenant.identify:authcode'];
        $routeCollection->add($route);
        $this->router->setRoutes($routeCollection);
    }
}