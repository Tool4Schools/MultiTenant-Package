## Introduction

- [Installation](#installation)


<a name="installation"></a>
## Installation

To get started, you will need to add the Tools4schools private Satis repository. 
add the Tools4schools repository to your application's `composer.json` file:

```json
"repositories": [
    {
        "type": "composer",
        "url": "https://packagist.tools4schools.org"
    }
],
```


install Tools4Schools MultiTenant via the Composer using the following command:

    composer require tools4schools/multitenant
    
or add `tools4schools/multitenant` to your list of required packages in your `composer.json` file:
```json
"require": {
    "php": "^7.2.5",
    "fideloper/proxy": "^4.2",
    "laravel/framework": "^7.0",
    "tools4schools/multitenant": "dev-master"
},
```                                                                          
After your `composer.json` file has been updated, run the `composer update` command in your console terminal:

```bash
composer update
```
    
The MultiTenant service provider registers its own database migration directory with the framework, so you should migrate your database after installing the package. The MultiTenant migrations will create the tables your application needs to store tenants and and tenant_users:    
    
    php artisan migrate
    
After running the `php artisan migrate` command, add the `Tools4Schools\MultiTenant\Traits\BelongsToTenant` trait to your `App\User` model. This trait will provide a few helper methods to your model which allow you to inspect the user's tenant memberships:

    <?php

    namespace App;

    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Tools4Schools\MultiTenant\Traits\BelongsToTenant;

    class User extends Authenticatable
    {
        use BelongsToTenant, Notifiable;
    } 
    
    
Next, you need to add the `IdentifyTenant` middleware to the `$routeMiddleware` property of your `app/Http/Kernel.php` file:

    protected $routeMiddleware = [
        'tenant.identify' =>\Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenant::class,
    ];  
    
and add the `IdentifyTenant` middleware to the `$middlewarePriority` property anywhere after `\Illuminate\Routing\Middleware\SubstituteBindings::class,`

     protected $middlewarePriority = [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\Authenticate::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authorize::class,
            \Tools4Schools\MultiTenant\Http\Middleware\IdentifyTenant::class,
        ];
        
        