<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="OrderView")
 * @ORM\Entity
 */
class Orderview
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
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="creating", type="integer", nullable=false)
     */
    private $creating;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="received", type="integer", nullable=false)
     */
    private $received;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="dispatched", type="integer", nullable=false)
     */
    private $dispatched;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="cancelled", type="integer", nullable=false)
     */
    private $cancelled;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="sentForRefund", type="integer", nullable=false)
     */
    private $sentforrefund;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="refunded", type="integer", nullable=false)
     */
    private $refunded;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="returned", type="integer", nullable=false)
     */
    private $returned;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="refundFailed", type="integer", nullable=false)
     */
    private $refundfailed;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="groupKey", type="string", length=45, nullable=false)
     */
    private $groupkey;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="shoppingCartKey", type="integer", nullable=false)
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

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="requestType", type="string", length=45, nullable=false)
     */
    private $requesttype;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="orderStatus", type="string", length=45, nullable=false)
     */
    private $orderstatus;
    
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
     * @ORM\Column(name="deliveryAddressKey", type="integer", nullable=false)
     */
    private $deliveryaddresskey;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="billingAddressKey", type="integer", nullable=true)
     */
    private $billingaddresskey;
    
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryFirstName", type="string", length=100, nullable=true)
     */
    private $deliveryfirstname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryLastName", type="string", length=100, nullable=true)
     */
    private $deliverylastname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryAddress1", type="string", length=200, nullable=false)
     */
    private $deliveryaddress1;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryAddress2", type="string", length=200, nullable=true)
     */
    private $deliveryaddress2;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryAddress3", type="string", length=200, nullable=true)
     */
    private $deliveryaddress3;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryPostCode", type="string", length=45, nullable=true)
     */
    private $deliverypostcode;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryCity", type="string", length=100, nullable=false)
     */
    private $deliverycity;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryState", type="string", length=100, nullable=false)
     */
    private $deliverystate;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryEmail", type="string", length=100, nullable=false)
     */
    private $deliveryemail;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryPhone", type="string", length=100, nullable=true)
     */
    private $deliveryphone;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="deliveryCountryKey", type="integer", nullable=false)
     */
    private $deliverycountrykey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryCountry", type="string", length=200, nullable=false)
     */
    private $deliverycountry;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="deliveryCountryCode", type="string", length=45, nullable=false)
     */
    private $deliverycountrycode;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingFirstName", type="string", length=100, nullable=true)
     */
    private $billingfirstname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingLastName", type="string", length=100, nullable=true)
     */
    private $billinglastname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingAddress1", type="string", length=200, nullable=false)
     */
    private $billingaddress1;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingAddress2", type="string", length=200, nullable=true)
     */
    private $billingaddress2;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingAddress3", type="string", length=200, nullable=true)
     */
    private $billingaddress3;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingPostCode", type="string", length=45, nullable=true)
     */
    private $billingpostcode;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingCity", type="string", length=100, nullable=false)
     */
    private $billingcity;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingState", type="string", length=100, nullable=false)
     */
    private $billingstate;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingEmail", type="string", length=100, nullable=false)
     */
    private $billingemail;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingPhone", type="string", length=100, nullable=true)
     */
    private $billingphone;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="billingCountryKey", type="integer", nullable=false)
     */
    private $billingcountrykey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingCountry", type="string", length=200, nullable=false)
     */
    private $billingcountry;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="billingCountryCode", type="string", length=45, nullable=false)
     */
    private $billingcountrycode;

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
    function getCreating() { return $this->creating; }
    function getReceived() { return $this->received; }
    function getDispatched() { return $this->dispatched; }
    function getCancelled() { return $this->cancelled; }
    function getSentforrefund() { return $this->sentforrefund; }
    function getRefunded() { return $this->refunded; }
    function getReturned() { return $this->returned; }
    function getRefundfailed() { return $this->refundfailed; }
    function getGroupkey() { return $this->groupkey; }
    function getShoppingcartkey() { return $this->shoppingcartkey; }
    function getTrackingnumber() { return $this->trackingnumber; }
    function getArrivaldatefrom() { return $this->arrivaldatefrom; }
    function getArrivaldateto() { return $this->arrivaldateto; }
    function getUpdateddate() { return $this->updateddate; }
    function getCreateddate() { return $this->createddate; }
    function getRequesttype() { return $this->requesttype; }
    function getOrderstatus() { return $this->orderstatus; }
    function getPackaging() { return $this->packaging; }
    function getAvailability() { return $this->availability; }
    function getWarehouse() { return $this->warehouse; }
    function getScreensize() { return $this->screensize; }
    function getAvailableamount() { return $this->availableamount; }
    function getRemainingamount() { return $this->remainingamount; }
    function getCropyear() { return $this->cropyear; }
    function getCuppingscore() { return $this->cuppingscore; }
    function getCurrency() { return $this->currency; }
    function getName() { return $this->name; }
    function getProducerstory() { return $this->producerstory; }
    function getProcessingmethod() { return $this->processingmethod; }
    function getCountry() { return $this->country; }
    function getRegion() { return $this->region; }
    function getProducer() { return $this->producer; }
    function getBaseunitsperpackage() { return $this->baseunitsperpackage; }
    function getMaxfreesamplequantity() { return $this->maxfreesamplequantity; }
    function getSensorialdescriptors() { return $this->sensorialdescriptors; }
    function getCultivars() { return $this->cultivars; }
    function getActive() { return $this->active; }
    function getIspurchase() { return $this->ispurchase; }
    function getIssample() { return $this->issample; }
    function getIspaidsample() { return $this->ispaidsample; }
    function getIsfreesample() { return $this->isfreesample; }
    function getDeliveryaddresskey() { return $this->deliveryaddresskey; }
    function getBillingaddresskey() { return $this->billingaddresskey; }
    function getDeliveryfirstname() { return $this->deliveryfirstname; }
    function getDeliverylastname() { return $this->deliverylastname; }
    function getDeliveryaddress1() { return $this->deliveryaddress1; }
    function getDeliveryaddress2() { return $this->deliveryaddress2; }
    function getDeliveryaddress3() { return $this->deliveryaddress3; }
    function getDeliverypostcode() { return $this->deliverypostcode; }
    function getDeliverycity() { return $this->deliverycity; }
    function getDeliverystate() { return $this->deliverystate; }
    function getDeliveryemail() { return $this->deliveryemail; }
    function getDeliveryphone() { return $this->deliveryphone; }
    function getDeliverycountrykey() { return $this->deliverycountrykey; }
    function getDeliverycountry() { return $this->deliverycountry; }
    function getDeliverycountrycode() { return $this->deliverycountrycode; }
    function getBillingfirstname() { return $this->billingfirstname; }
    function getBillinglastname() { return $this->billinglastname; }
    function getBillingaddress1() { return $this->billingaddress1; }
    function getBillingaddress2() { return $this->billingaddress2; }
    function getBillingaddress3() { return $this->billingaddress3; }
    function getBillingpostcode() { return $this->billingpostcode; }
    function getBillingcity() { return $this->billingcity; }
    function getBillingstate() { return $this->billingstate; }
    function getBillingemail() { return $this->billingemail; }
    function getBillingphone() { return $this->billingphone; }
    function getBillingcountrykey() { return $this->billingcountrykey; }
    function getBillingcountry() { return $this->billingcountry; }
    function getBillingcountrycode() { return $this->billingcountrycode; }

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
    function setCreating($val) { $this->creating = $val; }
    function setReceived($val) { $this->received = $val; }
    function setDispatched($val) { $this->dispatched = $val; }
    function setCancelled($val) { $this->cancelled = $val; }
    function setSentforrefund($val) { $this->sentforrefund = $val; }
    function setRefunded($val) { $this->refunded = $val; }
    function setReturned($val) { $this->returned = $val; }
    function setRefundfailed($val) { $this->refundfailed = $val; }
    function setGroupkey($val) { $this->groupkey = $val; }
    function setShoppingcartkey($val) { $this->shoppingcartkey = $val; } 
    function setTrackingnumber($val) { $this->trackingnumber = $val; }
    function setArrivaldatefrom($val) { $this->arrivaldatefrom = $val; }
    function setArrivaldateto($val) { $this->arrivaldateto = $val; }
    function setUpdateddate($val) { $this->updateddate = $val; } 
    function setCreateddate($val) { $this->createddate = $val; }
    function setRequesttype($val) { $this->requesttype = $val; }
    function setOrderstatus($val) { $this->orderstatus = $val; }
    function setPackaging($val) { $this->packaging = $val; }
    function setAvailability($val) { $this->availability = $val; }
    function setWarehouse($val) { $this->warehouse = $val; }
    function setScreensize($val) { $this->screensize = $val; }
    function setAvailableamount($val) { $this->availableamount = $val; }
    function setRemainingamount($val) { $this->remainingamount = $val; } 
    function setCropyear($val) { $this->cropyear = $val; }
    function setCuppingscore($val) { $this->cuppingscore = $val; }
    function setCurrency($val) { $this->currency = $val; }
    function setName($val) { $this->name = $val; }
    function setProducerstory($val) { $this->producerstory = $val; }
    function setProcessingmethod($val) { $this->processingmethod = $val; }
    function setCountry($val) { $this->country = $val; }
    function setRegion($val) { $this->region = $val; }
    function setProducer($val) { $this->producer = $val; }
    function setBaseunitsperpackage($val) { $this->baseunitsperpackage = $val; }
    function setMaxfreesamplequantity($val) { $this->maxfreesamplequantity = $val; }
    function setSensorialdescriptors($val) { $this->sensorialdescriptors = $val; }
    function setCultivars($val) { $this->cultivars = $val; }
    function setActive($val) { $this->active = $val; }
    function setIspurchase($val) { $this->ispurchase = $val; }
    function setIssample($val) { $this->issample = $val; }
    function setIspaidsample($val) { $this->ispaidsample = $val; }
    function setIsfreesample($val) { $this->isfreesample = $val; }
    function setDeliveryaddresskey($val) { $this->deliveryaddresskey = $val; }
    function setBillingaddresskey($val) { $this->billingaddresskey = $val; }
    function setDeliveryfirstname($val) { $this->deliveryfirstname = $val; }
    function setDeliverylastname($val) { $this->deliverylastname = $val; }
    function setDeliveryaddress1($val) { $this->deliveryaddress1 = $val; }
    function setDeliveryaddress2($val) { $this->deliveryaddress2 = $val; }
    function setDeliveryaddress3($val) { $this->deliveryaddress3 = $val; }
    function setDeliverypostcode($val) { $this->deliverypostcode = $val; }
    function setDeliverycity($val) { $this->deliverycity = $val; }
    function setDeliverystate($val) { $this->deliverystate = $val; }
    function setDeliveryemail($val) { $this->deliveryemail = $val; }
    function setDeliveryphone($val) { $this->deliveryphone = $val; }
    function setDeliverycountrykey($val) { $this->deliverycountrykey = $val; }
    function setDeliverycountry($val) { $this->deliverycountry = $val; }
    function setDeliverycountrycode($val) { $this->deliverycountrycode = $val; }
    function setBillingfirstname($val) { $this->billingfirstname = $val; }
    function setBillinglastname($val) { $this->billinglastname = $val; }
    function setBillingaddress1($val) { $this->billingaddress1 = $val; }
    function setBillingaddress2($val) { $this->billingaddress2 = $val; }
    function setBillingaddress3($val) { $this->billingaddress3 = $val; }
    function setBillingpostcode($val) { $this->billingpostcode = $val; }
    function setBillingcity($val) { $this->billingcity = $val; }
    function setBillingstate($val) { $this->billingstate = $val; }
    function setBillingemail($val) { $this->billingemail = $val; }
    function setBillingphone($val) { $this->billingphone = $val; }
    function setBillingcountrykey($val) { $this->billingcountrykey = $val; }
    function setBillingcountry($val) { $this->billingcountry = $val; }
    function setBillingcountrycode($val) { $this->billingcountrycode = $val; }
}