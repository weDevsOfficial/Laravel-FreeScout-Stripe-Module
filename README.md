# Laravel-FreeScout-Stripe-Module

A free FreeScout module to integrate Stripe with FreeScout. View critical customer information like Subscription, Invoice and Orders with this free FreeScout module of Stripe from weDevs

## Installation

### • Install as a laravel package

Add `weDevsOfficial/Laravel-FreeScout-Stripe-Module` package to your dependencies.

```bash
composer require "weDevsOfficial/Laravel-FreeScout-Stripe-Module"
```

After requiring package, add service provider of this package to providers in `config/app.php`.

```php
'providers' => array(
    // ...
    Modules\Stripe\Providers\StripeServiceProvider::class,
)
```

### • Install as a laravel Modules

If you want to use this package like a freescout modules, First you need to clone or download this package and put it inside the modules folder. Then need to install stripe client via composer

```php
   composer require stripe/stripe-php
```

For more information about modules development process and artisan command you can [Go nwidart modules development docs](https://nwidart.com/laravel-modules/v6/introduction).
## Configuration

### Migrations

After requiring the package, You need to publish migration file. You can publish it with following command.

```
php artisan vendor:publish --tag=stripe-migration
```

After publishing migration file, You need to run migration command

```
php artisan migrate
```

Laravel FreeScout Stripe Module package use laravel caching for stripe data. You can change caching timeout from config file. you can publish it with following command.

```
php artisan vendor:publish --tag=stripe-config
```

After publishing them, you can find config files (stripe.php) in your config folder. Now you can modify caching timeout according to your needs.
 

# Usage

To retrieving stripe data, put your stripe secret key in stripe settings page. this settings link locate at inbox settings menu list.
you can retrieve stripe data for each inbox. That's why you need to put the secret key for each inbox separately.
