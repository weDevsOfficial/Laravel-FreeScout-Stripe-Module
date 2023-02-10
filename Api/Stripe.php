<?php

namespace Modules\Stripe\Api;

use Illuminate\Support\Facades\Cache;
use Modules\Stripe\Api\StripeBase;

class Stripe
{
    public $stripe = '';
    protected $cacheExpiryTime = '';
    public function __construct($secretKey)
    {
        $this->stripe = StripeBase::conection($secretKey);
        $this->cacheExpiryTime = config('stripe.cache_expiry_time', 60);
    }

    /**
     * get All Subscription of user
     * @param mixed $email
     * @return mixed
     */
    public function getSubscriptions($email)
    {
        try {
            $key = "stripe_subscriptions_extra_{$email}";

            if (Cache::has($key)) {
                return Cache::get($key);
            } else {
                $subscriptions = $this->stripe->customer($email)->subscriptions()->get();
                Cache::put($key, $subscriptions, $this->cacheExpiryTime);

                return $subscriptions;
            }
        } catch(\Exception $e) {
            return 'Message: ' .$e->getMessage();
        }
    }

    /**
     * Get All Invoice of user
     * @param mixed $email
     * @return mixed
     */
    public function getInvoices($email)
    {
        try {
            $key = "stripe_invoice_{$email}";

            if (Cache::has($key)) {
                return Cache::get($key);
            } else {
                $invoices = $this->stripe->customer($email)->invoices()->get();

                Cache::put($key, $invoices, $this->cacheExpiryTime);

                return $invoices;
            }
        } catch(\Exception $e) {
            return 'Message: ' .$e->getMessage();
        }
    }
}
