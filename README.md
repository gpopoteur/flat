# Flat

***WORK IN PROGRESS!!***

Laravel 5 package for creating multi-tenant apps. As of right now only Postgresql Schemas are supported.

## Install

Install via composer with the command:

    composer require gpopoteur/flat

Then register the provider in the `config/app.php` file:

    'GPopoteur\Flat\FlatServiceProvider',

After that you can start using the `Flat` API! :)

## Usage

You can inject the dependency with Laravel IoC container:

    public function __contruct(Flat $flat){
        // business logic
    }

Or just let the `App::make()` resolve the class.

    $flat = App::make('GPopoteur\Flat\Flat');

### Creating a new Flat

To create a new Flat (Tenant), just call the `build($name)` method of the `Flat` class passing the new Tenant name.

    $flat->build('new-tenant');

After you create a new tenant, the new Schema is not automatically migrated, to run the migrations just run:

    $flat->migrate('new-tenant');

or, if migrating several tenants at once, pass an array with the tenants names:

    $flat->migrate(['new-tenant', 'other-tenant', 'and-another']);

## Changing Flats (Tenants)

To change the Tenant programatically you can call the `moveIn($name)` method of the `Flat` API.

    $flat->moveIn('new-tenant');

There is a middleware implemented called `flatCheckIn`, basically what it does is to take the name of the variable `flatName` and change the user to that Schema.

Example:

### Subdomain tenant

To be able to do a subdomain tenant, just add a `flatName` variable and the `flatCheckIn` middleware to your domain route group.

    Route::group(['domain' => '{flatName}.domain.com', 'middleware' => 'flatCheckIn'], function(){

        // everything inside this closure will be done in the `flatName` schema.

    });

### Route name tenant

Because the `flatName` variable is assigned in the route, you can add that variable to any route group that fits your need, example:

    Route::group(['prefix' => 'account/{flatName}', 'middleware' => 'flatCheckIn'], function(){

        // everything inside this closure will be done in the `flatName` schema.

    });

When using the provided Middlewares, if a tenant doesn't exists the middleware will throw a new `FlatDoesntExistsException` that you can catch globally in the app and then redirect the user somewhere and show then a nice error message.

### Contributing

Contributions to the library are more than welcome. To contribute code to this repo, follow this simple steps:

1. Fork this project.
2. Create a new branch. Ex: `feature/this-thing`
3. Commit & Push the changes to your repo.
4. Do a Pull Request from your branch to the `develop` branch of this repo.

Thanks :)

### Disclaimer

Because this package works with the database, and the data is the more critical part of a production app I make the following statement: 

> I am not responsible in any way for any harm that this library does to your data. This package comes as-is. Use at your own risk.

### License

MIT