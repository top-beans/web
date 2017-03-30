<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Clients")
 * @ORM\Entity
 */
class Client
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="clientKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $clientkey;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="clientcode", type="string", length=100, nullable=true)
     */
    private $clientcode;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=300, nullable=true)
     */
    private $address;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=45, nullable=true)
     */
    private $postcode;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=45, nullable=true)
     */
    private $number;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=300, nullable=true)
     */
    private $website;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=100, nullable=true)
     */
    private $twitter;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=100, nullable=true)
     */
    private $facebook;
    
    /**
     * @Type("DateTime<'Y-m-d'>")
     * @var \DateTime
     *
     * @ORM\Column(name="incorporationDate", type="datetime", nullable=true)
     */
    private $incorporationdate;
    
    /**
     * @Type("double")
     * @var decimal
     *
     * @ORM\Column(name="turnover", type="decimal", nullable=true)
     */
    private $turnover;
    
    /**
     * @Type("double")
     * @var decimal
     *
     * @ORM\Column(name="profit", type="decimal", nullable=true)
     */
    private $profit;
    
    /**
     * @Type("double")
     * @var decimal
     *
     * @ORM\Column(name="loss", type="decimal", nullable=true)
     */
    private $loss;
    
    /**
     * @Type("double")
     * @var decimal
     *
     * @ORM\Column(name="netCurrentAssets", type="decimal", nullable=true)
     */
    private $netcurrentassets;
    
    /**
     * @Type("double")
     * @var decimal
     *
     * @ORM\Column(name="shareHoldersFunds", type="decimal", nullable=true)
     */
    private $shareholdersfunds;
    
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="approvedForBusiness", type="integer", nullable=false)
     */
    private $approvedforbusiness;

    function getClientkey () { return $this->clientkey; } 
    function getClientcode () { return $this->clientcode; } 
    function getName() { return $this->name; } 
    function getAddress() { return $this->address; } 
    function getPostcode() { return $this->postcode; } 
    function getEmail() { return $this->email; } 
    function getNumber() { return $this->number; } 
    function getMobile() { return $this->mobile; } 
    function getWebsite() { return $this->website; } 
    function getTwitter() { return $this->twitter; } 
    function getFacebook() { return $this->facebook; } 
    function getRegion() { return $this->region; } 
    function getIncorporationdate() { return $this->incorporationdate; } 
    function getTurnover() { return $this->turnover; } 
    function getProfit() { return $this->profit; } 
    function getLoss() { return $this->loss; } 
    function getNetcurrentassets() { return $this->netcurrentassets; } 
    function getShareholdersfunds() { return $this->shareholdersfunds; } 
    function getApprovedforbusiness() { return $this->approvedforbusiness; } 

    function setClientkey ($val) { $this->clientkey  = $val; } 
    function setClientcode ($val) { $this->clientcode  = $val; } 
    function setName($val) { $this->name = $val; } 
    function setAddress($val) { $this->address = $val; } 
    function setPostcode($val) { $this->postcode = $val; } 
    function setEmail($val) { $this->email = $val; } 
    function setNumber($val) { $this->number = $val; } 
    function setMobile($val) { $this->mobile = $val; } 
    function setWebsite($val) { $this->website = $val; } 
    function setTwitter($val) { $this->twitter = $val; } 
    function setFacebook($val) { $this->facebook = $val; } 
    function setRegion($val) { $this->region = $val; } 
    function setIncorporationdate($val) { $this->incorporationdate = $val; } 
    function setTurnover($val) { $this->turnover = $val; } 
    function setProfit($val) { $this->profit = $val; } 
    function setLoss($val) { $this->loss = $val; } 
    function setNetcurrentassets($val) { $this->netcurrentassets = $val; } 
    function setShareholdersfunds($val) { $this->shareholdersfunds = $val; } 
    function setApprovedforbusiness($val) { $this->approvedforbusiness = $val; } 
}
