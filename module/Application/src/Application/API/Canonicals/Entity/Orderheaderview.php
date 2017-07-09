<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="OrderHeaderView")
 * @ORM\Entity
 */
class Orderheaderview
{
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="groupKey", type="string", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $groupkey;

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
     * @ORM\Column(name="totalItems", type="integer", nullable=false)
     */
    private $totalitems;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allPurchase", type="integer", nullable=false)
     */
    private $allpurchase;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allSample", type="integer", nullable=false)
     */
    private $allsample;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allPaidSample", type="integer", nullable=false)
     */
    private $allpaidsample;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allFreeSample", type="integer", nullable=false)
     */
    private $allfreesample;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allCreating", type="integer", nullable=false)
     */
    private $allcreating;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allReceived", type="integer", nullable=false)
     */
    private $allreceived;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allDispatched", type="integer", nullable=false)
     */
    private $alldispatched;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allCancelled", type="integer", nullable=false)
     */
    private $allcancelled;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allSentForRefund", type="integer", nullable=false)
     */
    private $allsentforrefund;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allRefunded", type="integer", nullable=false)
     */
    private $allrefunded;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allReturned", type="integer", nullable=false)
     */
    private $allreturned;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allRefundFailed", type="integer", nullable=false)
     */
    private $allrefundfailed;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="allMixed", type="integer", nullable=false)
     */
    private $allmixed;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="totalPrice", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $totalprice;
    
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
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="billingDifferent", type="integer", nullable=false)
     */
    private $billingdifferent;

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

    function getGroupkey() { return $this->groupkey; }
    function getCustomerkey() { return $this->customerkey; }
    function getTotalitems() { return $this->totalitems; }
    function getAllpurchase() { return $this->allpurchase; }
    function getAllsample() { return $this->allsample; }
    function getAllpaidsample() { return $this->allpaidsample; }
    function getAllfreesample() { return $this->allfreesample; }
    function getAllcreating() { return $this->allcreating; }
    function getAllreceived() { return $this->allreceived; }
    function getAlldispatched() { return $this->alldispatched; }
    function getAllcancelled() { return $this->allcancelled; }
    function getAllsentforrefund() { return $this->allsentforrefund; }
    function getAllrefunded() { return $this->allrefunded; }
    function getAllreturned() { return $this->allreturned; }
    function getAllrefundfailed() { return $this->allrefundfailed; }
    function getAllmixed() { return $this->allmixed; }
    function getTotalprice() { return $this->totalprice; }
    function getUpdateddate() { return $this->updateddate; }
    function getCreateddate() { return $this->createddate; }
    function getBillingdifferent() { return $this->billingdifferent; }
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

    function setGroupkey($val) { $this->groupkey = $val; }
    function setCustomerkey($val) { $this->customerkey = $val; }
    function setTotalitems($val) { $this->totalitems = $val; }
    function setAllpurchase($val) { $this->allpurchase = $val; }
    function setAllsample($val) { $this->allsample = $val; }
    function setAllpaidsample($val) { $this->allpaidsample = $val; }
    function setAllfreesample($val) { $this->allfreesample = $val; }
    function setAllcreating($val) { $this->allcreating = $val; }
    function setAllreceived($val) { $this->allreceived = $val; }
    function setAlldispatched($val) { $this->alldispatched = $val; }
    function setAllcancelled($val) { $this->allcancelled = $val; }
    function setAllsentforrefund($val) { $this->allsentforrefund = $val; }
    function setAllrefunded($val) { $this->allrefunded = $val; }
    function setAllreturned($val) { $this->allreturned = $val; }
    function setAllrefundfailed($val) { $this->allrefundfailed = $val; }
    function setAllmixed($val) { $this->allmixed = $val; }
    function setTotalprice($val) { $this->totalprice = $val; }
    function setUpdateddate($val) { $this->updateddate = $val; } 
    function setCreateddate($val) { $this->createddate = $val; }
    function setBillingdifferent($val) { $this->billingdifferent = $val; }
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