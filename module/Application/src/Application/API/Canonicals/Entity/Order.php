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
     * @ORM\Column(name="totalPrice", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $totalprice;

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
     * @ORM\Column(name="requestTypeypeKey", type="integer", nullable=false)
     */
    private $requesttypeypekey;

    function getOrderkey() { return $this->orderkey; } 
    function getQuantity() { return $this->quantity; } 
    function getPrice() { return $this->price; } 
    function getPricebaseunit() { return $this->pricebaseunit; } 
    function getPackagingunit() { return $this->packagingunit; } 
    function getTotalprice() { return $this->totalprice; } 
    function getCoffeekey() { return $this->coffeekey; } 
    function getCustomerkey() { return $this->customerkey; } 
    function getRequesttypeypekey() { return $this->requesttypeypekey; } 

    function setOrderkey($val) { $this->orderkey = $val; } 
    function setQuantity($val) { $this->quantity = $val; } 
    function setPrice($val) { $this->price = $val; } 
    function setPricebaseunit($val) { $this->pricebaseunit = $val; } 
    function setPackagingunit($val) { $this->packagingunit = $val; } 
    function setTotalprice($val) { $this->totalprice = $val; } 
    function setCoffeekey($val) { $this->coffeekey = $val; } 
    function setCustomerkey($val) { $this->customerkey = $val; } 
    function setRequesttypeypekey($val) { $this->requesttypeypekey = $val; } 
}

