<?php

namespace Modules\Stripe\Providers;

use App\Conversation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;
use Modules\Stripe\Api\Stripe;
use Modules\Stripe\Entities\StripeSetting;
use View;

//Module alias
define('STRIPE_MODULE', 'stripe');

class StripeServiceProvider extends ServiceProvider
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
        //
    }

    /**
     * Boot the application events.
     *
     * @return void
     */

    public function boot()
    {
        $this->registerConfig();
        $this->registerAssets();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
        $this->hooks();
        $this->registerTranslations();
        $this->registerMigration();
    }

    protected function registerMigration()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->publishes([
            __DIR__ . '/../Database/Migrations/2023_02_07_091128_create_stripe_settings_table.php' => database_path('migrations/'. date('Y_m_d_His', time()).'_create_stripe_settings_table.php'),
        ], 'stripe-migration');
    }
    /**
    * Register config.
    *
    * @return void
    */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/stripe.php' => config_path('stripe.php'),
        ], 'stripe-config');

        $this->mergeConfigFrom(
            __DIR__.'/../Config/stripe.php',
            'stripe'
        );
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        \Eventy::addFilter('stylesheets', function ($styles) {
            $styles[] = \Module::getPublicPath(STRIPE_MODULE).'/css/stripe.css';
            return $styles;
        });

        \Eventy::addAction('customer.profile.extra', [$this, 'customerProfileExtra']);

        \Eventy::addAction('mailboxes.settings.menu', [$this, 'mailboxSettingsMenu']);
    }

    /**
     * Show data in customer Profile Extra section
     * @param mixed $customer
     * @return void
     */
    public function customerProfileExtra($customer)
    {
        $stripeSecret = $this->getStripeSecretKey($customer->getMainEmail());

        if(!empty($stripeSecret)) {
            $productWithInvoices = $this->getStripeInvoices($customer->getMainEmail(), $stripeSecret);
            $productWithSubscriptions = $this->getStripeSubscriptions($customer->getMainEmail(), $stripeSecret);

            echo View::make('stripe::customer_fields_view', [
                'productWithInvoices' => $productWithInvoices,
                'productWithSubscriptions' => $productWithSubscriptions
            ])->render();
        }
    }

    public function getStripeSecretKey($email)
    {
        $customer = Conversation::where('customer_email', $email)->first();
        $mailboxID = isset($customer->mailbox) ? $customer->mailbox->id : '';

        $stripeSettings = StripeSetting::select('stripe_secret_key')->where('mailbox_id', $mailboxID)->first();

        if (isset($stripeSettings)) {
            return Crypt::decryptString($stripeSettings->stripe_secret_key);
        } else {
            return '';
        }
    }

    /**
     * Get Stripe Subscription
     * @param mixed $email
     * @return mixed
     */
    public function getStripeInvoices($email, $stripeSecret)
    {
        $stripe = new Stripe($stripeSecret);
        $stripeInvoices = $stripe->getInvoices($email);

        return $stripeInvoices;

    }

    /**
     * Get Stripe Invoice
     * @param mixed $email
     * @return mixed
     */
    public function getStripeSubscriptions($email, $stripeSecret)
    {
        $stripe = new Stripe($stripeSecret);
        $stripeSubscriptions = $stripe->getSubscriptions($email);

        return $stripeSubscriptions;

    }

    /**
    * Show data in customer Profile Extra section
    * @param mixed $customer
    * @return void
    */
    public function mailboxSettingsMenu($mailbox)
    {
        echo View::make('stripe::mailbox_settings_menu', [
            'mailbox' => $mailbox,
        ])->render();
    }

    /**
     * Register views.
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/stripe');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/stripe';
        }, \Config::get('view.paths')), [$sourcePath]), 'stripe');
    }


    /**
     * Register views.
     * @return void
     */
    public function registerAssets()
    {
        $this->publishes([
            __DIR__.'/../Public/css' => public_path('modules/stripe/css'),
        ], 'public');
    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/stripe');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'stripe');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'stripe');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
