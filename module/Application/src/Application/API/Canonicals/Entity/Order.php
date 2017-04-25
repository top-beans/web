<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="Orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @var integer
     *
     * @ORM\Column(name="orderKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderkey;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="priceBaseUnit", type="string", length=45, nullable=false)
     */
    private $pricebaseunit;

    /**
     * @var string
     *
     * @ORM\Column(name="packagingUnit", type="string", length=45, nullable=false)
     */
    private $packagingunit;

    /**
     * @var string
     *
     * @ORM\Column(name="itemPrice", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $itemprice;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="coffeeKey", type="integer", nullable=false)
     */
    private $coffeekey;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="customerKey", type="integer", nullable=false)
     */
    private $customerkey;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="requestTypeKey", type="integer", nullable=false)
     */
    private $requestypekey;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="statusKey", type="integer", nullable=false)
     */
    private $statuskey;

    /**
     * @var string
     *
     * @ORM\Column(name="groupKey", type="string", length=45, nullable=false)
     */
    private $groupkey;

    function getOrderkey() { return $this->orderkey; }
    function getQuantity() { return $this->quantity; }
    function getPrice() { return $this->price; }
    function getPricebaseunit() { return $this->pricebaseunit; }
    function getPackagingunit() { return $this->packagingunit; }
    function getItemprice() { return $this->itemprice; }
    function getCoffeekey() { return $this->coffeekey; }
    function getCustomerkey() { return $this->customerkey; }
    function getRequestypekey() { return $this->requestypekey; }
    function getStatuskey() { return $this->statuskey; }
    function getGroupkey() { return $this->groupkey; } 
    
    function setOrderkey($val) { $this->orderkey = $val; } 
    function setQuantity($val) { $this->quantity = $val; } 
    function setPrice($val) { $this->price = $val; } 
    function setPricebaseunit($val) { $this->pricebaseunit = $val; } 
    function setPackagingunit($val) { $this->packagingunit = $val; } 
    function setItemprice($val) { $this->itemprice = $val; } 
    function setCoffeekey($val) { $this->coffeekey = $val; } 
    function setCustomerkey($val) { $this->customerkey = $val; } 
    function setRequestypekey($val) { $this->requestypekey = $val; } 
    function setStatuskey($val) { $this->statuskey = $val; } 
    function setGroupkey($val) { $this->groupkey = $val; } 
}

