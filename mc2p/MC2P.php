<?php

namespace MyChoice2Pay;

require_once('Request.php');
require_once('Objects.php');
require_once('Resources.php');
require_once('Notification.php');

/**
 * MC2P - class used to manage the communication with MyChoice2Pay API
 */
class APIClient 
{
    protected $apiRequest;

    /**
     * @param string   $key
     * @param string   $secret
     */
    public function __construct($key, $secret) 
    {
        $this->apiRequest = new APIRequest($key, $secret);

        $this->product = new ProductResource($this->apiRequest);
        $this->plan = new PlanResource($this->apiRequest);
        $this->tax = new TaxResource($this->apiRequest);
        $this->shipping = new ShippingResource($this->apiRequest);
        $this->coupon = new CouponResource($this->apiRequest);
        $this->transaction = new TransactionResource($this->apiRequest);
        $this->subscription = new SubscriptionResource($this->apiRequest);
        $this->sale = new SaleResource($this->apiRequest);
        $this->currency = new CurrencyResource($this->apiRequest);
        $this->gateway = new GatewayResource($this->apiRequest);
        $this->payData = new PayDataResource($this->apiRequest);
    }

    /**
     * @param string    $class; Object class name
     * @param object    $resource; Resource instance
     * @param array     $payload
     */
    private function __wrapper ($class, $resource, $payload) 
    {
        return new $class ($payload, $resource, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Product ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Product', $this->product, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Plan ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Plan', $this->plan, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Tax ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Tax', $this->tax, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Shipping ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Shipping', $this->shipping, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Coupon ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Coupon', $this->coupon, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Transaction ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Transaction', $this->transaction, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Subscription ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Subscription', $this->subscription, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Sale ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Sale', $this->sale, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Currency ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Currency', $this->currency, $payload);
    }

    /**
     * @param array   $payload
     */
    public function Gateway ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\Gateway', $this->gateway, $payload);
    }

    /**
     * @param array   $payload
     */
    public function PayData ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\PayData', $this->payData, $payload);
    }

    /**
     * @param array   $payload
     */
    public function NotificationData ($payload = array()) 
    {
        return $this->__wrapper('MyChoice2Pay\NotificationData', $this, $payload);
    }
}
