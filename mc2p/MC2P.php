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

        $this->product = new ProductResource($this->apiRequest, '/product/', 'MyChoice2Pay\Product');
        $this->plan = new PlanResource($this->apiRequest, '/plan/', 'MyChoice2Pay\Plan');
        $this->tax = new TaxResource($this->apiRequest, '/tax/', 'MyChoice2Pay\Tax');
        $this->shipping = new ShippingResource($this->apiRequest, '/shipping/', 'MyChoice2Pay\Shipping');
        $this->coupon = new CouponResource($this->apiRequest, '/coupon/', 'MyChoice2Pay\Coupon');
        $this->transaction = new TransactionResource($this->apiRequest, '/transaction/', 'MyChoice2Pay\Transaction');
        $this->subscription = new SubscriptionResource($this->apiRequest, '/subscription/', 'MyChoice2Pay\Subscription');
        $this->currency = new CurrencyResource($this->apiRequest, '/currency/', 'MyChoice2Pay\Currency');
        $this->gateway = new GatewayResource($this->apiRequest, '/gateway/', 'MyChoice2Pay\Gateway');
        $this->payData = new PayDataResource($this->apiRequest, '/pay/', 'MyChoice2Pay\PayData');
        $this->sale = new SaleResource($this->apiRequest, '/sale/', 'MyChoice2Pay\Sale');
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
