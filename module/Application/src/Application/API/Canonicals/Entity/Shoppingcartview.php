<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="ShoppingCartView")
 * @ORM\Entity
 */
class Shoppingcartview
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
     * @ORM\Column(name="quantity", type="integer", nullable=false)
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
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="checkedOut", type="integer", nullable=false)
     */
    private $checkedout;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="updatedDate", type="datetime", nullable=false)
     */
    private $updateddate;

    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetime", nullable=false)
     */
    private $createddate;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="requestType", type="string", length=45, nullable=true)
     */
    private $requesttype;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="packaging", type="string", length=45, nullable=true)
     */
    private $packaging;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="availability", type="string", length=45, nullable=true)
     */
    private $availability;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="warehouse", type="string", length=45, nullable=true)
     */
    private $warehouse;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="screenSize", type="string", length=45, nullable=true)
     */
    private $screensize;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="availableAmount", type="integer", nullable=false)
     */
    private $availableamount;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="cropYear", type="string", length=45, nullable=true)
     */
    private $cropyear;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="cuppingScore", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $cuppingscore;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=45, nullable=false)
     */
    private $currency = '£';

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
     * @ORM\Column(name="itemPrice", type="decimal", precision=30, scale=2, nullable=false)
     */
    private $itemprice;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="producerStory", type="text", length=65535, nullable=false)
     */
    private $producerstory;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="processingMethod", type="string", length=45, nullable=true)
     */
    private $processingmethod;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=45, nullable=true)
     */
    private $country;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=45, nullable=true)
     */
    private $region;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="producer", type="string", length=45, nullable=true)
     */
    private $producer;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="priceBaseUnit", type="string", length=45, nullable=true)
     */
    private $pricebaseunit;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="packagingUnit", type="string", length=45, nullable=true)
     */
    private $packagingunit;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="baseUnitsPerPackage", type="integer", nullable=false)
     */
    private $baseunitsperpackage;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="isPurchase", type="integer", nullable=false)
     */
    private $ispurchase;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="isSample", type="integer", nullable=false)
     */
    private $issample;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="isPaidSample", type="integer", nullable=false)
     */
    private $ispaidsample;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="isFreeSample", type="integer", nullable=false)
     */
    private $isfreesample;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="maxFreeSampleQuantity", type="integer", nullable=false)
     */
    private $maxfreesamplequantity;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="sensorialDescriptors", type="text", length=65535, nullable=true)
     */
    private $sensorialdescriptors;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="cultivars", type="text", length=65535, nullable=true)
     */
    private $cultivars;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="active", type="integer", nullable=false)
     */
    private $active;

    function getShoppingcartkey() { return $this->shoppingcartkey; }
    function getCookiekey() { return $this->cookiekey; }
    function getCoffeekey() { return $this->coffeekey; }
    function getQuantity() { return $this->quantity; }
    function getRequesttypekey() { return $this->requesttypekey; }
    function getCheckedout() { return $this->checkedout; }
    function getUpdateddate() { return $this->updateddate; }
    function getCreateddate() { return $this->createddate; }
    function getRequesttype() { return $this->requesttype; }
    function getPackaging() { return $this->packaging; }
    function getAvailability() { return $this->availability; }
    function getWarehouse() { return $this->warehouse; }
    function getScreensize() { return $this->screensize; }
    function getAvailableamount() { return $this->availableamount; }
    function getCropyear() { return $this->cropyear; }
    function getCuppingscore() { return $this->cuppingscore; }
    function getCurrency() { return $this->currency; }
    function getPrice() { return $this->price; }
    function getItemprice() { return $this->itemprice; }
    function getName() { return $this->name; }
    function getProducerstory() { return $this->producerstory; }
    function getProcessingmethod() { return $this->processingmethod; }
    function getCountry() { return $this->country; }
    function getRegion() { return $this->region; }
    function getProducer() { return $this->producer; }
    function getPricebaseunit() { return $this->pricebaseunit; }
    function getPackagingunit() { return $this->packagingunit; }
    function getBaseunitsperpackage() { return $this->baseunitsperpackage; }
    function getIspurchase() { return $this->ispurchase; }
    function getIssample() { return $this->issample; }
    function getIspaidsample() { return $this->ispaidsample; }
    function getIsfreesample() { return $this->isfreesample; }
    function getMaxfreesamplequantity() { return $this->maxfreesamplequantity; }
    function getSensorialdescriptors() { return $this->sensorialdescriptors; }
    function getCultivars() { return $this->cultivars; }
    function getActive() { return $this->active; }

    function setShoppingcartkey($val) { $this->shoppingcartkey = $val; } 
    function setCookiekey($val) { $this->cookiekey = $val; } 
    function setCoffeekey($val) { $this->coffeekey = $val; } 
    function setQuantity($val) { $this->quantity = $val; } 
    function setRequesttypekey($val) { $this->requesttypekey = $val; }
    function setCheckedout($val) { $this->checkedout = $val; } 
    function setUpdateddate($val) { $this->updateddate = $val; } 
    function setCreateddate($val) { $this->createddate = $val; } 
    function setRequesttype($val) { $this->requesttype = $val; } 
    function setPackaging($val) { $this->packaging = $val; } 
    function setAvailability($val) { $this->availability = $val; } 
    function setWarehouse($val) { $this->warehouse = $val; } 
    function setScreensize($val) { $this->screensize = $val; } 
    function setAvailableamount($val) { $this->availableamount = $val; } 
    function setCropyear($val) { $this->cropyear = $val; } 
    function setCuppingscore($val) { $this->cuppingscore = $val; } 
    function setCurrency($val) { $this->currency = $val; } 
    function setPrice($val) { $this->price = $val; } 
    function setItemprice($val) { $this->itemprice = $val; } 
    function setName($val) { $this->name = $val; } 
    function setProducerstory($val) { $this->producerstory = $val; } 
    function setProcessingmethod($val) { $this->processingmethod = $val; } 
    function setCountry($val) { $this->country = $val; } 
    function setRegion($val) { $this->region = $val; } 
    function setProducer($val) { $this->producer = $val; } 
    function setPricebaseunit($val) { $this->pricebaseunit = $val; } 
    function setPackagingunit($val) { $this->packagingunit = $val; }
    function setBaseunitsperpackage($val) { $this->baseunitsperpackage = $val; }
    function setIspurchase($val) { $this->ispurchase = $val; }
    function setIssample($val) { $this->issample = $val; }
    function setIspaidsample($val) { $this->ispaidsample = $val; }
    function setIsfreesample($val) { $this->isfreesample = $val; }
    function setMaxfreesamplequantity($val) { $this->maxfreesamplequantity = $val; }
    function setSensorialdescriptors($val) { $this->sensorialdescriptors = $val; } 
    function setCultivars($val) { $this->cultivars = $val; } 
    function setActive($val) { $this->active = $val; } 
}

