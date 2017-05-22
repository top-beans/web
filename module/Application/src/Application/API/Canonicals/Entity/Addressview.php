<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="AddressView")
 * @ORM\Entity
 */
class Addressview
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="addressKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $addresskey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=100, nullable=true)
     */
    private $lastname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="address1", type="string", length=200, nullable=false)
     */
    private $address1;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=200, nullable=true)
     */
    private $address2;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="address3", type="string", length=200, nullable=true)
     */
    private $address3;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="postCode", type="string", length=45, nullable=true)
     */
    private $postcode;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=false)
     */
    private $city;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100, nullable=false)
     */
    private $state;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="countryKey", type="integer", nullable=false)
     */
    private $countrykey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="countryName", type="string", length=200, nullable=false)
     */
    private $countryname;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=45, nullable=false)
     */
    private $countrycode;

    function getAddresskey() { return $this->addresskey; } 
    function getFirstname() { return $this->firstname; } 
    function getLastname() { return $this->lastname; } 
    function getAddress1() { return $this->address1; } 
    function getAddress2() { return $this->address2; } 
    function getAddress3() { return $this->address3; } 
    function getPostcode() { return $this->postcode; } 
    function getCity() { return $this->city; } 
    function getState() { return $this->state; } 
    function getEmail() { return $this->email; } 
    function getPhone() { return $this->phone; } 
    function getCountrykey() { return $this->countrykey; } 
    function getCountryname() { return $this->countryname; } 
    function getCountrycode() { return $this->countrycode; } 

    function setAddresskey($val) { $this->addresskey = $val; } 
    function setFirstname($val) { $this->firstname = $val; } 
    function setLastname($val) { $this->lastname = $val; } 
    function setAddress1($val) { $this->address1 = $val; } 
    function setAddress2($val) { $this->address2 = $val; } 
    function setAddress3($val) { $this->address3 = $val; } 
    function setPostcode($val) { $this->postcode = $val; } 
    function setCity($val) { $this->city = $val; } 
    function setState($val) { $this->state = $val; } 
    function setEmail($val) { $this->email = $val; } 
    function setPhone($val) { $this->phone = $val; } 
    function setCountrykey($val) { $this->countrykey = $val; } 
    function setCountryname($val) { $this->countryname = $val; } 
    function setCountrycode($val) { $this->countrycode = $val; } 
}

