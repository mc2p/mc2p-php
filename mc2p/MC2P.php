<?php

function decorator($class, $resource)
{
    $payload = $class::get();
    $obj = new $class($payload, $resource);
    return $obj;
}

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
        
        $this->product = ProductResource($this->apiRequest);
        $this->plan = PlanResource($this->apiRequest);
        $this->tax = TaxResource($this->apiRequest);
        $this->shipping = ShippingResource($this->apiRequest);
        $this->coupon = CouponResource($this->apiRequest);
        $this->transaction = TransactionResource($this->apiRequest);
        $this->subscription = SubscriptionResource($this->apiRequest);
        $this->sale = SaleResource($this->apiRequest);
        $this->currency = CurrencyResource($this->apiRequest);
        $this->gateway = GatewayResource($this->apiRequest);
        $this->payData = PayDataResource($this->apiRequest);   

        $this->Product = decorator('Product', $this->product);
        $this->Plan = decorator('Plan', $this->plan);
        $this->Tax = decorator('Tax', $this->tax);
        $this->Shipping = decorator('Shipping', $this->shipping);
        $this->Coupon = decorator('Coupon', $this->coupon);
        $this->Transaction = decorator('Transaction', $this->transaction);
        $this->Subscription = decorator('Subscription', $this->subscription);
        $this->Sale = decorator('Sale', $this->sale);
        $this->Currency = decorator('Currency', $this->currency);
        $this->Gateway = decorator('Gateway', $this->gateway);
        $this->PayData = decorator('PayData', $this->payData);

        $this->NotificationData = decorator('NotificationData', $this);
    }

}