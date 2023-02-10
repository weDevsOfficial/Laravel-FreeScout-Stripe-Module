# Laravel-FreeScout-Stripe-Module
A free FreeScout module to integrate Stripe with FreeScout. View critical customer information like Subscription, Invoice and Orders with this free FreeScout module of Stripe from weDevs


## Installation

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

## Configuration

### Migrations

After requiring the package, You need to run migration command

```
php artisan migrate
```

Laravel FreeScout Stripe Module package use laravel caching for stripe data. You can change caching timeout from config file. you can publish it with following command.

```
php artisan vendor:publish --tag=stripe
```

After publishing them, you can find config files (stripe.php) in your config folder. now you can modify caching timeout according to your needs.
 
# Usage 
To retrieving stripe data, put your stripe secret key in stripe settings page. this settings link locate at inbox settings menu list. 
you can retrieve stripe data for each inbox. That's why you need to put the secret key for each inbox separately.