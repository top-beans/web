<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Coffees")
 * @ORM\Entity
 */
class Coffee
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
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="availableAmount", type="string", length=45, nullable=false)
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
     * @Type("decimal")
     * @var decimal
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
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

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

    function getPackaging() { return $this->packaging; }
    function getAvailability() { return $this->availability; }
    function getWarehouse() { return $this->warehouse; }
    function getScreensize() { return $this->screensize; }
    function getAvailableamount() { return $this->availableamount; }
    function getCropyear() { return $this->cropyear; }
    function getCuppingscore() { return $this->cuppingscore; }
    function getCurrency() { return $this->currency; }
    function getPrice() { return $this->price; }
    function getName() { return $this->name; }
    function getDescription() { return $this->description; }
    function getProcessingmethod() { return $this->processingmethod; }
    function getCountry() { return $this->country; }
    function getRegion() { return $this->region; }
    function getProducer() { return $this->producer; }
    function getPricebaseunit() { return $this->pricebaseunit; }
    function getSensorialdescriptors() { return $this->sensorialdescriptors; }
    function getCultivars() { return $this->cultivars; }
    function getActive() { return $this->active; }

    function setPackaging($val) { $this->packaging = $val; } 
    function setAvailability($val) { $this->availability = $val; } 
    function setWarehouse($val) { $this->warehouse = $val; } 
    function setScreensize($val) { $this->screensize = $val; } 
    function setAvailableamount($val) { $this->availableamount = $val; } 
    function setCropyear($val) { $this->cropyear = $val; } 
    function setCuppingscore($val) { $this->cuppingscore = $val; } 
    function setCurrency($val) { $this->currency = $val; } 
    function setPrice($val) { $this->price = $val; } 
    function setName($val) { $this->name = $val; } 
    function setDescription($val) { $this->description = $val; } 
    function setProcessingmethod($val) { $this->processingmethod = $val; } 
    function setCountry($val) { $this->country = $val; } 
    function setRegion($val) { $this->region = $val; } 
    function setProducer($val) { $this->producer = $val; } 
    function setPricebaseunit($val) { $this->pricebaseunit = $val; } 
    function setSensorialdescriptors($val) { $this->sensorialdescriptors = $val; } 
    function setCultivars($val) { $this->cultivars = $val; } 
    function setActive($val) { $this->active = $val; } 
}

