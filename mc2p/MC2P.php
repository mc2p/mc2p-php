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

        $this->Product = decorator('MyChoice2Pay\Product', $this->product);
        $this->Plan = decorator('MyChoice2Pay\Plan', $this->plan);
        $this->Tax = decorator('MyChoice2Pay\Tax', $this->tax);
        $this->Shipping = decorator('MyChoice2Pay\Shipping', $this->shipping);
        $this->Coupon = decorator('MyChoice2Pay\Coupon', $this->coupon);
        $this->Transaction = decorator('MyChoice2Pay\Transaction', $this->transaction);
        $this->Subscription = decorator('MyChoice2Pay\Subscription', $this->subscription);
        $this->Sale = decorator('MyChoice2Pay\Sale', $this->sale);
        $this->Currency = decorator('MyChoice2Pay\Currency', $this->currency);
        $this->Gateway = decorator('MyChoice2Pay\Gateway', $this->gateway);
        $this->PayData = decorator('MyChoice2Pay\PayData', $this->payData);

        $this->NotificationData = decorator('MyChoice2Pay\NotificationData', $this);
    }
}

function decorator($class, $resource)
{
    $payload = $class::get();
    $obj = new $class($payload, $resource);
    return $obj;
}
