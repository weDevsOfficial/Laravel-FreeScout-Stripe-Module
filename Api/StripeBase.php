<?php

namespace Modules\Stripe\Api;

use Stripe\StripeClient;

class StripeBase
{
    private static $instance;
    protected $stripe;
    protected $result;

    private function __construct(StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get Stripe Connection
     * @param mixed $secretKey
     * @return StripeBase
     */
    public static function conection($secretKey)
    {
        if (!self::$instance) {
            self::$instance = new self(new StripeClient($secretKey));
        }

        return self::$instance;
    }

    /**
     * Get all Stripe customer
     * @param mixed $email
     * @return StripeBase
     */
    public function customer($email)
    {
        $this->result = $this->stripe->customers->all(["email" => $email]);

        return $this;
    }

    /**
     * Get All Invoices 
     * @return StripeBase
     */
    public function invoices()
    {
        $customerIds = array_map(function ($item) {
            return $item->id;
        }, $this->result->data);

        $this->result = [];
        foreach ($customerIds as $customerId) {
            $invoices = $this->stripe->invoices->all(["customer" => $customerId, "limit" => 5]);
            foreach ($invoices->data as $invoice) {
                $productId = $invoice->lines->data[0]->plan->product;
                $product = $this->getProduct($productId);
                if (!isset($this->result[$product->name])) {
                    $this->result[$product->name] = [];
                }

                $invoice->currency_symbol = $this->getCurrencySymbol($invoice->currency);

                array_push($this->result[$product->name], $invoice);
            }
        }

        return $this;
    }

    /**
     * Get All Subscription
     * @return StripeBase
     */
    public function subscriptions()
    {
        $customerIds = array_map(function ($item) {
            return $item->id;
        }, $this->result->data);

        $this->result = [];
        foreach ($customerIds as $customerId) {
            $subscriptions = $this->stripe->subscriptions->all(["customer" => $customerId, "limit" => 5]);
            foreach ($subscriptions->data as $subscription) {
                $productId = $subscription->items->data[0]->plan->product;
                $product = $this->getProduct($productId);
                if (!isset($this->result[$product->name])) {
                    $this->result[$product->name] = [];
                }

                $subscription->currency_symbol = $this->getCurrencySymbol($subscription->currency);

                array_push($this->result[$product->name], $subscription);
            }
        }

        return $this;
    }

    /**
     * Get Stripe Product details
     * @param mixed $productId
     * @return \Stripe\Product
     */
    private function getProduct($productId)
    {
        return $this->stripe->products->retrieve(
            $productId
        );
    }

    /**
     * Get Currency symbool
     * @param mixed $currency
     * @return bool|string
     */
    private function getCurrencySymbol($currency)
    {
        $locale = App()->getLocale();
        $fmt = new \NumberFormatter($locale."@currency=$currency", \NumberFormatter::CURRENCY);
        return $fmt->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * Return $this instance result
     * @return array|mixed
     */
    public function get()
    {
        return $this->result;
    }
}
