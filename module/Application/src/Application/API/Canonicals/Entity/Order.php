<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="orderKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderkey;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="priceBaseUnit", type="string", length=45, nullable=false)
     */
    private $pricebaseunit;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="packagingUnit", type="string", length=45, nullable=false)
     */
    private $packagingunit;

    /**
     * @Type("string")
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
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="groupKey", type="string", length=45, nullable=false)
     */
    private $groupkey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="cancellationCode", type="string", length=45, nullable=false)
     */
    private $cancellationcode;

    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="codeRequestTime", type="datetime", nullable=true)
     */
    private $coderequesttime;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="codeConfirmTime", type="datetime", nullable=true)
     */
    private $codeconfirmtime;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="worldpayOrderCode", type="string", length=100, nullable=true)
     */
    private $worldpayordercode;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="shoppingCartKey", type="integer", nullable=true)
     */
    private $shoppingcartkey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="trackingNumber", type="string", length=100, nullable=true)
     */
    private $trackingnumber;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="arrivalDateFrom", type="datetime", nullable=true)
     */
    private $arrivaldatefrom;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="arrivalDateTo", type="datetime", nullable=true)
     */
    private $arrivaldateto;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="updatedDate", type="datetime", nullable=true)
     */
    private $updateddate;

    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetime", nullable=false)
     */
    private $createddate;

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
    function getCancellationcode() { return $this->cancellationcode; } 
    function getCoderequesttime() { return $this->coderequesttime; }
    function getCodeconfirmtime() { return $this->codeconfirmtime; }
    function getWorldpayordercode() { return $this->worldpayordercode; }
    function getShoppingcartkey() { return $this->shoppingcartkey; }
    function getTrackingnumber() { return $this->trackingnumber; }
    function getArrivaldatefrom() { return $this->arrivaldatefrom; }
    function getArrivaldateto() { return $this->arrivaldateto; }
    function getUpdateddate() { return $this->updateddate; }
    function getCreateddate() { return $this->createddate; }
    
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
    function setCancellationcode($val) { $this->cancellationcode = $val; } 
    function setCoderequesttime($val) { $this->coderequesttime = $val; }
    function setCodeconfirmtime($val) { $this->codeconfirmtime = $val; }
    function setWorldpayordercode($val) { $this->worldpayordercode = $val; }
    function setShoppingcartkey($val) { $this->shoppingcartkey = $val; } 
    function setTrackingnumber($val) { $this->trackingnumber = $val; }
    function setArrivaldatefrom($val) { $this->arrivaldatefrom = $val; }
    function setArrivaldateto($val) { $this->arrivaldateto = $val; }
    function setUpdateddate($val) { $this->updateddate = $val; } 
    function setCreateddate($val) { $this->createddate = $val; } 
}

