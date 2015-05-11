# Flat

### WORK IN PROGRESS!!

Laravel 5 package for creating multi-tenant apps. As of right now only Postgresql Schemas are supported.

## Install

Install via composer with the command:

    composer require gpopoteur/flat

Then register the provider in the `config/app.php` file:

    'GPopoteur\Flat\FlatServiceProvider',

After that you can start using the `Flat` API! :)

## Changing Flats (Tenants)

There is a middleware implemented called `flatCheckIn`, basically what it does is to take the name of the variable `flatName` and move change the user to that Schema.

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