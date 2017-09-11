<?php

namespace MyChoice2Pay;

require_once('Mixins.php');

/**
 * Paginator - class used on list requests
 */
class Paginator
{
    protected $resource;
    protected $result;  
    protected $count;   

    private $previous;
    private $next;

    /**
     * @param array    $payload
     * @param string   $objectItemClass
     * @param string   $resource
     */
    public function __construct($payload, $objectItemClass, $resource) 
    {
        $this->count = (isset($payload['count'])) ? $payload['count'] : 0;
        $this->previous = (isset($payload['previous'])) ? $payload['previous'] : 0;
        $this->next = (isset($payload['next'])) ? $payload['next'] : 0;

        $this->results = array();
        $results = (isset($payload['results'])) ? $payload['results'] : array();
        
        foreach ($results as $val)
        {
            $objectItem = new $objectItemClass($val, $resource);
            array_push($this->results, $objectItem);
        }

        $this->resource = $resource;
    }

    /**
     * Paginator object with the previous items
     */
    public function getPreviousList() 
    {
        return (isset($this->previous))
            ? $this->resource->itemList($this->previous)
            : null;
    }

    /**
     * Paginator object with the next items
     */
    public function getNextList() 
    {
        return (isset($this->next))
            ? $this->resource->itemList($this->next)
            : null;
    }
}

/**
 * Object item - class used to wrap the data from API that represent an item
 */
class ObjectItem extends ObjectItemMixin
{
    protected $mixin;
    protected $payload;
    protected $resource;

    private $deleted = false;

    /**
     * @param array    $payload
     * @param string   $resource
     */
    public function __construct($payload, $resource) 
    {
        $this->payload = (isset($payload)) ? $payload: array();
        $this->resource = $resource;
    }

    /**
     * Allows use the following syntax to get a field of the object: $obj->name
     *
     * @param string   $name
     */
    public function __get(string $name) 
    {
        return $this->payload[$name];
    }

    /**
     * Allows use the following syntax to get a field of the object: $obj->name = $value
     *
     * @param string   $name
     * @param string   $value
     */
    public function __set(string $name , mixed $value) 
    {
        switch ($name) {
            case 'payload':
                $this->payload = $value;
                break;
            case 'resource':
                $this->resource = $value;
                break;
            case 'deleted':
                $this->deleted = $value;
                break;
            default:
                $this->payload[$key] = $value;
                break;
        }
    }
}

/**
 * Object item - class used to wrap the data from API that represent an item
 */
class ReadOnlyObjectItem extends ObjectItem
{
    protected $retrieveMixin;

    /**
     * @param array    $payload
     * @param string   $resource
     */
    public function __construct ($payload, $resource) 
    {
        $this->retrieveMixin = new RetrieveObjectItemMixin();
        parent::__construct($payload, $resource);
    }

    /**
     * Retrieve object with object_id and return
     *
     * @param string   $objectId
     */
    public static function get ($objectId) 
    {
        $payload = array(
            $this->retrieveMixin->ID_PROPERTY => $objectId,
        );

        $obj = new self($payload, self::resource);
        $obj.retrieve();

        return $obj;
    }

    /**
     * Retrieves the data of the object item using RetrieveObjectItemMixin
     */
    public function retrieve()
    {
        $this->retrieveMixin->retrieve();
    }
}

/**
 * Object item that allows retrieve and create an item
 */
class CRObjectItem extends ReadOnlyObjectItem
{
    protected $createMixin;

    /**
     * @param array    $payload
     * @param string   $resource
     */
    public function __construct ($payload, $resource) 
    {
        $this->createMixin = new CreateObjectItemMixin();
        parent::__construct($payload, $resource);
    }

    /**
     * Creates the object item with the payload data
     */
    private function __create()
    {
        $this->createMixin->__create();
    }
 
    /**
     * Executes the internal function _create if the object item don't have id
     */
    public function save()
    {
        $this->createMixin->save();
    }
}

/**
 * Object item that allows retrieve, create and change an item
 */
class CRUObjectItem extends CRObjectItem
{
    protected $saveMixin;

    /**
     * @param array    $payload
     * @param string   $resource
     */
    public function __construct ($payload, $resource) 
    {
        $this->saveMixin = new SaveObjectItemMixin();
        parent::__construct($payload, $resource);
    }

    /**
     * Creates the object item with the payload data
     */
    private function __create()
    {
        $this->createMixin->__create();
    }
 
    /**
     * Changes the object item with the payload data
     */
    private function __change()
    {
        $this->saveMixin->__create();
    }

    /**
     * Executes the internal function _create if the object item don't have id
     */
    public function save()
    {
        $this->saveMixin->save();
    }
}

/**
 * Object item that allows retrieve, create and change an item
 */
class CRUDObjectItem extends CRUObjectItem
{
    protected $deleteMixin;

    /**
     * @param array    $payload
     * @param string   $resource
     */
    public function __construct ($payload, $resource) 
    {
        $this->deleteMixin = new DeleteObjectItemMixin();
        parent::__construct($payload, $resource);
    }

    /**
     * Deletes the object item
     */
    public function delete()
    {
        $this->deleteMixin->delete();
    }
}

/**
 * Object item that allows retrieve, create and to get pay_url based on token of an item
 */
 class PayURLCRObjectItem extends CRObjectItem
{
    protected $payURLMixin;

    /**
     * @param array    $payload
     * @param string   $resource
     */
    public function __construct ($payload, $resource) 
    {
        $this->payURLMixin = new PayURLMixin();
        parent::__construct($payload, $resource);
    }

    public function getPayUrl()
    {   
        return $this->payURLMixin->getPayUrl();
    }
    
    public function getIframeUrl()
    {   
        return $this->payURLMixin->getIframeUrl();
    }
}

/**
 * Resource - class used to manage the requests to the API related with a resource
 * ex: product
 */
class Resource extends ResourceMixin
{
    const PAGINATOR_CLASS = 'Paginator';

    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $this->apiRequest = $apiRequest;
    }
}

/**
 * Resource that allows send requests of detail
 */
class DetailOnlyResource extends Resource
{
    protected $doResourceMixin;

    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $doResourceMixin = new DetailOnlyResourceMixin();
        parent::__construct($apiRequest);
    }

    /**
     * @param string $resourceId
     * @return array Object item from server
     */
    public function detail($resourceId) 
    {
        return $doResourceMixin->detail($resourceId);
    }
}

/**
 * Resource that allows send requests of list and detail
 */
class ReadOnlyResource extends Resource
{
    protected $roResourceMixin;

    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $roResourceMixin = new ReadOnlyResourceMixin();
        parent::__construct($apiRequest);
    }

    /**
     * @param array $absUrl
     * @return array Object item from server
     */
    public function itemList($absUrl) 
    {
        return $roResourceMixin->itemList($absUrl);
    }
}

/**
 * Resource that allows send requests of create, list and detail
 */
class CRResource extends ReadOnlyResource
{
    protected $createResourceMixin;

    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $createResourceMixin = new CreateResourceMixin();
        parent::__construct($apiRequest);
    }

    /**
     * @param array $data
     * @return array Object item from server
     */
    public function create($data)
    {
        return $createResourceMixin->create($data);
    }
}

/**
 * Resource that allows send requests of delete, change, create, list and detail
 */
class CRUDResource extends CRResource
{
    protected $changeResourceMixin;
    protected $deleteResourceMixin;

    /**
     * @param array    $apiRequest
     */
    public function __construct ($apiRequest) 
    {
        $changeResourceMixin = new ChangeResourceMixin();
        $deleteResourceMixin = new DeleteResourceMixin();
        parent::__construct($apiRequest);
    }

    /**
     * @param string $resourceId
     * @param array $data
     * @return array Object item from server
     */
    public function change($resourceId, $data)
    {
        return $changeResourceMixin->change($resourceId, $data);
    }

    /**
     * @param string $resourceId
     * @return array Object item from server
     */
     public function delete($resourceId)
     {
         return $deleteResourceMixin->delete($resourceId);
     }
}
