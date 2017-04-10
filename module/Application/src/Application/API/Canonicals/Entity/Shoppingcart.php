<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="ShoppingCart")
 * @ORM\Entity
 */
class Shoppingcart
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="shoppingCartKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shoppingcartkey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="cookieKey", type="string", length=45, nullable=false)
     */
    private $cookiekey;

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
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="requestTypeKey", type="integer", nullable=false)
     */
    private $requesttypekey;

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

    function getShoppingcartkey() { return $this->shoppingcartkey; }
    function getCookiekey() { return $this->cookiekey; }
    function getCoffeekey() { return $this->coffeekey; }
    function getQuantity() { return $this->quantity; }
    function getRequesttypekey() { return $this->requesttypekey; }
    function getUpdateddate() { return $this->updateddate; }
    function getCreateddate() { return $this->createddate; }

    function setShoppingcartkey($val) { $this->shoppingcartkey = $val; } 
    function setCookiekey($val) { $this->cookiekey = $val; } 
    function setCoffeekey($val) { $this->coffeekey = $val; } 
    function setQuantity($val) { $this->quantity = $val; } 
    function setRequesttypekey($val) { $this->requesttypekey = $val; } 
    function setUpdateddate($val) { $this->updateddate = $val; } 
    function setCreateddate($val) { $this->createddate = $val; } 
}

