<?php

namespace MyChoice2Pay;

require_once('Base.php');
require_once('Mixins.php');
require_once('Objects.php');

/**
 * Product resource
 */
class ProductResource extends CRUDResource
{
    const PATH = '/product/';
    const OBJECT_ITEM_CLASS = 'Product';
}

/**
 * Plan resource
 */
class PlanResource extends CRUDResource
{
    const PATH = '/plan/';
    const OBJECT_ITEM_CLASS = 'Plan';
}

/**
 * Tax resource
 */
class TaxResource extends CRUDResource
{
    const PATH = '/tax/';
    const OBJECT_ITEM_CLASS = 'Tax';
}

/**
 * Shipping resource
 */
class ShippingResource extends CRUDResource
{
    const PATH = '/shipping/';
    const OBJECT_ITEM_CLASS = 'Shipping';
}

/**
 * Coupon resource
 */
class CouponResource extends CRUDResource
{
    const PATH = '/coupon/';
    const OBJECT_ITEM_CLASS = 'Coupon';
}

/**
 * Transaction resource
 */
class TransactionResource extends CRResource
{
    const PATH = '/transaction/';
    const OBJECT_ITEM_CLASS = 'Transaction';
}

/**
 * Subscription resource
 */
class SubscriptionResource extends CRResource
{
    const PATH = '/subscription/';
    const OBJECT_ITEM_CLASS = 'Subscription';
}

/**
 * Currency resource
 */
class CurrencyResource extends ReadOnlyResource
{
    const PATH = '/currency/';
    const OBJECT_ITEM_CLASS = 'Currency';
}
 
/**
 * Gateway resource
 */
class GatewayResource extends ReadOnlyResource
{
    const PATH = '/gateway/';
    const OBJECT_ITEM_CLASS = 'Gateway';
}
 
/**
 * Sale resource
 */
class SaleResource extends ReadOnlyResource
{
    const PATH = '/sale/';
    const OBJECT_ITEM_CLASS = 'Sale';

    protected $rCVResourceMixin;
    
    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $rCVResourceMixin = new RefundCaptureVoidResourceMixin();
        parent::__construct($apiRequest);
    }
        
    /**
     * Refund the object item
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function refund(Array $data = null)
    {
        return $this->rCVResourceMixin->refund($data);
    }

    /**
     * Capture the object item
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function capture(Array $data = null)
    {
        return $this->rCVResourceMixin->capture($data);
    }

    /**
     * Void the object item
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function void(Array $data = null)
    {
        return $this->rCVResourceMixin->void($data);
    }
}
  
/**
 * PayData resource
 */
class PayDataResource extends DetailOnlyResource
{
    const PATH = '/pay/';
    const OBJECT_ITEM_CLASS = 'PayData';

    protected $cardShareResourceMixin;
    
    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $cardShareResourceMixin = new CardShareResourceMixin();
        parent::__construct($apiRequest);
    }

    /**
     * Send card details
     * 
     * @param array $gatewayCode
     * @param array $data
     * @return array Object item from server
     */
    public function card(string $gatewayCode, array $data = null)
    {
        return $this->cardShareResourceMixin->card($gatewayCode, $data);
    }
     
    /**
     * Send share details
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function share(array $data = null)
    {
        return $this->cardShareResourceMixin->share($data);        
    }
}
