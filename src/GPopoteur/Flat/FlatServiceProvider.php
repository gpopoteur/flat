<?php 

namespace GPopoteur\Flat;

use GPopoteur\Flat\Middleware\FlatCheckInMiddleware;
use Illuminate\Support\ServiceProvider;

class FlatServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('GPopoteur\Flat\Contract\Flat', 'GPopoteur\Flat\Flat');
        $this->app->bind('GPopoteur\Flat\Schemas\Schema', 'GPopoteur\Flat\Schemas\PostgresSchema');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'GPopoteur\Flat\Flat'
        ];
    }
}
