<?php

/**
 * Basic info of the object item
 */
class ObjectItemMixin
{
    protected $payload;
    protected $resource;
    private $_deleted = False;
    
    const ID_PROPERTY = 'id';

    public function __toString()
    {
        return __CLASS__." {$this->payload}";
    }

    private function __hasID()
    {
        if (!isset($this->payload[self::ID_PROPERTY]))
        {
            throw new BadUseMC2PError('Object don\'t have ID');
        }
        return true;
    }

    private function __isNotDeleted()
    {
        if ($this->_deleted === true) 
        {
            throw new BadUseMC2PError('Object has been deleted');            
        }
        return false;
    }

    private function getId() 
    {
        if ($this->__hasID() && $this->__isNotDeleted()) 
        {
            return $this->payload[self::ID_PROPERTY];
        }
    }
}


/**
 * Allows delete an object item
 */
class DeleteObjectItemMixin extends ObjectItemMixin
{
    /**
     * Deletes the object item
     */
    public function delete()
    {
        $id = $this->getId();
        $this->resource->delete($id);
        $this->_deteled = true;
    }
}

/**
 * Allows retrieve an object item
 */
class RetrieveObjectItemMixin extends ObjectItemMixin
{
    /**
     * Retrieves the data of the object item
     */
    public function retrieve()
    {
        $id = $this->getId();
        $obj = $this->resource->detail($id);
        $this->payload = $obj->payload;
    }
}

/**
 * Allows create an object item
 */
class CreateObjectItemMixin extends ObjectItemMixin
{
    /**
     * Creates the object item with the json_dict data
     */
    private function __create()
    {
        $obj = $this->resource->create($this->payload);
        $this->payload = $obj->payload;
    }

    /**
     * Executes the internal function _create if the object item don't have id
     */
    public function save()
    {
        if (!$this->__hasID())
        {
            $this->__create();
        }
    }
}


/**
 * Allows change an object item
 */
class SaveObjectItemMixin extends CreateObjectItemMixin
{
    /**
     * Changes the object item with the json_dict data
     */
    private function __change()
    {
        $id = $this->getId();
        $obj = $this->resource->change($id, $this->payload);
        $this->payload = $obj->payload;
    }

    /**
     * Executes the internal function _create if the object item don't have id
     */
    public function save()
    {
        if ($this->__hasID())
        {
            $this->__change();
        } else {
            $this->__create();
        }
    }
}
  

/**
 * Allows make refund, capture and void an object item
 */
class RefundCaptureVoidObjectItemMixin extends ObjectItemMixin
{
    /**
     * Refund the object item
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function refund(Array $data = null)
    {
        $id = $this->getId();
        return $this->resource->refund($id, $data);
    }

    /**
     * Capture the object item
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function capture(Array $data = null)
    {
        $id = $this->getId();
        return $this->resource->capture($id, $data);
    }

    /**
     * Void the object item
     * 
     * @param array $data
     * @return array Object item from server
     */
    public function void(Array $data = null)
    {
        $id = $this->getId();
        return $this->resource->void($id, $data);
    }
}

/**
 * Allows make card and share an object item
 */
class CardShareObjectItemMixin extends ObjectItemMixin
{
    /**
     * Send card details
     * 
     * @param array $gatewayCode
     * @param array $data
     * @return array Object item from server
     */
    public function card(string $gatewayCode, array $data = null)
    {
        $id = $this->getId();
        return $this->resource->card($id, $gatewayCode, $this->payload);
    }
    
    /**
     * Send share details
     * 
     * @param array $data
     * @return array Object item from server
     */
     public function share(array $data = null)
    {
        $id = $this->getId();
        return $this->resource->share($id, $this->payload);
    }
}

/**
 * Add property to get pay_url based on token
 */
class PayURLMixin extends ObjectItemMixin
{
    const PAY_URL = 'https://pay.mychoice2pay.com/#/';
    
    public function getPayUrl()
    {   
        if ($this->__hasID() && $this->__isNotDeleted()) 
        {
            $token = $this->payload['token'];
            return self::PAY_URL."{$token}";
        }
    }
    
    public function getIframeUrl()
    {   
        $url = $this->getPayUrl();
        return "{$url}/iframe";
    }
}

/*
 * Basic info of the resource
 */
class ResourceMixin 
{
    const PATH =                '/resource/';
    const OBJECT_ITEM_CLASS =   null;
    const PAGINATOR_CLASS =     null;

    protected $apiRequest;
        
    /**
     * @param string $resourceId
     * @return array url to request or change an item
     */
    public function getDetailUrl(string $resourceId)
    {   
        return self::PATH.$resourceId;
    }
        
    /**
     * Help function to make a request that return one item
     * 
     * @param array $func
     * @param array $data
     * @param string $resourceId
     * @return array Object item from server
     */
    private function __oneItem($func, array $data = null, string $resourceId = null)
    {   
        if (!isset($resourceId))
        {
            $url = self::PATH;
        } else {
            $url = $this->getDetailUrl($resourceId);
        }

        $array = call_user_func($func, $url, $data, $this, $resourceId);

        return $this->getObjectItem($array);
    }

    public function getObjectItem($array)
    {
        $class = self::OBJECT_ITEM_CLASS;
        return new $class($array, $this);
    }

    public function getPaginator($payload)
    {
        $class = self::PAGINATOR_CLASS;
        return new $class($payload, $this);
    }
}


/*
 * Allows send requests of detail
 */
class DetailOnlyResourceMixin extends ResourceMixin 
{
    /**
     * @param string $resourceId
     * @return array Object item from server
     */
    public function detail($resourceId) 
    {
        $func = array($this->apiRequest, 'get');
        return $this->__oneItem($func, null, $resourceId);
    }
}

/*
 * Allows send requests of list and detail
 */
class ReadOnlyResourceMixin extends DetailOnlyResourceMixin 
{
    /**
     * @param array $absUrl
     * @return array Object item from server
     */
    public function itemList($absUrl)
    {
        if (isset($absUrl))
        {
            $payload = $this->apiRequest->get(null, null, $absUrl, $this);
        } else {
            $payload = $this->apiRequest->get(self::PATH, null, null, $this);            
        }
        
        return $this->getPaginator($payload, $this);
    }
}

/*
 * Allows send requests of create
 */
class CreateResourceMixin extends ResourceMixin 
{
    /**
     * @param array $data
     * @return array Object item from server
     */
    public function create($data) 
    {
        $func = array($this->apiRequest, 'post');
        return $this->__oneItem($func, $data);
    }
}

/*
 * Allows send requests of change
 */
class ChangeResourceMixin extends ResourceMixin 
{
    /**
     * @param string $resourceId
     * @param array $data
     * @return array Object item from server
     */
    public function change($resourceId, $data) 
    {
        $func = array($this->apiRequest, 'patch');
        return $this->__oneItem($func, $data, $resourceId);
    }
}

/*
 * Allows send requests of delete
 */
class DeleteResourceMixin extends ResourceMixin 
{
    /**
     * @param string $resourceId
     * @param array $data
     * @return array Object item from server
     */
    public function delete($resourceId) 
    {
        $func = array($this->apiRequest, 'delete');
        return $this->__oneItem($func, null, $resourceId);
    }
}

/*
 * Allows send requests of actions
 */
class ActionsResourceMixin extends ResourceMixin 
{
    /**
     * @param string $resourceId
     * @param array $action
     */
    public function getDetailActionUrl($resourceId, $action)
    {
        return self::PATH."{$resourceId}/{$action}/";
    }

    /**
     * @param string $resourceId
     * @param array $data
     */
    private function __oneItemAction($func, $resourceId, $action, $data = null) 
    {
        $url = $this->getDetailActionUrl($resourceId, $action);
        return call_user_func($url, $data, $this, $resourceId);
    }
}

/*
 * Allows send action requests of refund, capture and void
 */
class RefundCaptureVoidResourceMixin extends ActionsResourceMixin
{
    /**
     * @param string $resourceId
     * @param array $data
     */
    public function refund($resourceId, $data) 
    {
        $func = array($this->apiRequest, 'post200');
        return $this->__oneItemAction($func, $resourceId, 'refund', $data);
    }

    /**
     * @param string $resourceId
     * @param array $data
     */
    public function capture($resourceId, $data) 
    {
        $func = array($this->apiRequest, 'post200');
        return $this->__oneItemAction($func, $resourceId, 'capture', $data);
    }

    /**
     * @param string $resourceId
     * @param array $data
     */
    public function void($resourceId, $data) 
    {
        $func = array($this->apiRequest, 'post200');
        return $this->__oneItemAction($func, $resourceId, 'void', $data);
    }
}


/*
 * Allows send action requests of card and share
 */
class CardShareResourceMixin extends ActionsResourceMixin
{
    /**
     * @param string $resourceId
     * @param array $data
     */
    public function card($resourceId, $gatewayCode, $data) 
    {
        $func = array($this->apiRequest, 'post');
        $action = "card/{$gatewayCode}";
        return $this->__oneItemAction($func, $resourceId, $action, $data);
    }

    /**
     * @param string $resourceId
     * @param array $data
     */
    public function share($resourceId, $data) 
    {
        $func = array($this->apiRequest, 'post');
        return $this->__oneItemAction($func, $resourceId, 'share', $data);
    }
}