# Flat

### WORK IN PROGRESS!!

Laravel 5 package for creating multi-tenant apps. As of right now only Postgresql Schemas are supported.

## Install

...


## Changing Flats (Tenants)

There is a middleware implemented called `flatCheckIn`, basically what it does is to take the name of the variable `flatName` and move change the user to that Schema.

Example:

### Subdomain Tenant

    Route::group(['domain' => '{flatName}.domain.com', 'middleware' => 'flatCheckIn'], function(){

        // everything inside this closure will be done in the `flatName` schema.

    });

Because the `flatName` variable is assigned in the route, you can add that variable to any route group that fits your need, example:

    Route::group(['prefix' => 'account/{flatName}', 'middleware' => 'flatCheckIn'], function(){

        // everything inside this closure will be done in the `flatName` schema.

    });