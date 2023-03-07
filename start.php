<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

if (!defined('STRIPE_MODULE_DIR')) {
    define('STRIPE_MODULE_DIR', __DIR__);
}

require_once('vendor/autoload.php');

if (!app()->routesAreCached()) {
    require __DIR__ . '/Http/routes.php';
}
