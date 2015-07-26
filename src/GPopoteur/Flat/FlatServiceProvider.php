<?php 

namespace GPopoteur\Flat;

use GPopoteur\Flat\Schemas\PostgresSchema;
use GPopoteur\Flat\Schemas\Schema;
use GPopoteur\Flat\Schemas\SqliteSchema;
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
        $schemaManager = $this->getSchemaManager($this->app);

        $this->app->bind('GPopoteur\Flat\Contract\Flat', 'GPopoteur\Flat\Flat');
        $this->app->bind('GPopoteur\Flat\Schemas\Schema', function($app) use ($schemaManager) {
            return $schemaManager;
        });
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

    /**
     * Returns the Flat Schema Manager
     *
     * @param $app
     * @return Schema
     */
    private function getSchemaManager($app)
    {
        $database   = $app['db'];
        $artisan    = $app['Illuminate\Contracts\Console\Kernel'];
        $driver     = $app['config']['database']['default'];
        $storage    = $app['files'];
        $config     = $app['config'];

        switch ($driver) {
            case 'pgsql':
                return new PostgresSchema($database, $artisan);

            case 'sqlite':
                $sqliteSchema = new SqliteSchema($database, $artisan);
                $sqliteSchema->setStorage($storage);
                $sqliteSchema->setConfig($config);
                return $sqliteSchema;
        }
    }
}
