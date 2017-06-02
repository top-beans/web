<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="CoffeeView")
 * @ORM\Entity
 */
class CoffeeView
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="coffeeKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $coffeekey;

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
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="remainingAmount", type="integer", nullable=false)
     */
    private $remainingamount;

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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

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
     * @ORM\Column(name="producerStory", type="text", length=65535, nullable=false)
     */
    private $producerstory;

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

    function getCoffeekey() { return $this->coffeekey; }
    function getPackaging() { return $this->packaging; }
    function getAvailability() { return $this->availability; }
    function getWarehouse() { return $this->warehouse; }
    function getScreensize() { return $this->screensize; }
    function getAvailableamount() { return $this->availableamount; }
    function getRemainingamount() { return $this->remainingamount; }
    function getCropyear() { return $this->cropyear; }
    function getCuppingscore() { return $this->cuppingscore; }
    function getCurrency() { return $this->currency; }
    function getPrice() { return $this->price; }
    function getName() { return $this->name; }
    function getProcessingmethod() { return $this->processingmethod; }
    function getCountry() { return $this->country; }
    function getRegion() { return $this->region; }
    function getProducer() { return $this->producer; }
    function getProducerstory() { return $this->producerstory; }
    function getPricebaseunit() { return $this->pricebaseunit; }
    function getPackagingunit() { return $this->packagingunit; }
    function getBaseunitsperpackage() { return $this->baseunitsperpackage; }
    function getMaxfreesamplequantity() { return $this->maxfreesamplequantity; }
    function getSensorialdescriptors() { return $this->sensorialdescriptors; }
    function getCultivars() { return $this->cultivars; }
    function getActive() { return $this->active; }

    function setCoffeekey($val) { $this->coffeekey = $val; } 
    function setPackaging($val) { $this->packaging = $val; } 
    function setAvailability($val) { $this->availability = $val; } 
    function setWarehouse($val) { $this->warehouse = $val; } 
    function setScreensize($val) { $this->screensize = $val; } 
    function setAvailableamount($val) { $this->availableamount = $val; } 
    function setRemainingamount($val) { $this->remainingamount = $val; } 
    function setCropyear($val) { $this->cropyear = $val; } 
    function setCuppingscore($val) { $this->cuppingscore = $val; } 
    function setCurrency($val) { $this->currency = $val; } 
    function setPrice($val) { $this->price = $val; } 
    function setName($val) { $this->name = $val; } 
    function setProcessingmethod($val) { $this->processingmethod = $val; } 
    function setCountry($val) { $this->country = $val; } 
    function setRegion($val) { $this->region = $val; } 
    function setProducer($val) { $this->producer = $val; } 
    function setProducerstory($val) { $this->producerstory = $val; } 
    function setPricebaseunit($val) { $this->pricebaseunit = $val; } 
    function setPackagingunit($val) { $this->packagingunit = $val; }
    function setBaseunitsperpackage($val) { $this->baseunitsperpackage = $val; }
    function setMaxfreesamplequantity($val) { $this->maxfreesamplequantity = $val; }
    function setSensorialdescriptors($val) { $this->sensorialdescriptors = $val; } 
    function setCultivars($val) { $this->cultivars = $val; } 
    function setActive($val) { $this->active = $val; } 
}

