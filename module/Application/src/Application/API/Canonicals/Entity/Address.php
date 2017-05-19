<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Addresses")
 * @ORM\Entity
 */
class Address
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
     * @ORM\Column(name="fullName", type="string", length=200, nullable=true)
     */
    private $fullname;

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

    function getAddresskey() { return $this->addresskey; } 
    function getFullname() { return $this->fullname; } 
    function getAddress1() { return $this->address1; } 
    function getAddress2() { return $this->address2; } 
    function getPostcode() { return $this->postcode; } 
    function getCity() { return $this->city; } 
    function getEmail() { return $this->email; } 
    function getPhone() { return $this->phone; } 
    function getCountrykey() { return $this->countrykey; } 

    function setAddresskey($val) { $this->addresskey = $val; } 
    function setFullname($val) { $this->fullname = $val; } 
    function setAddress1($val) { $this->address1 = $val; } 
    function setAddress2($val) { $this->address2 = $val; } 
    function setPostcode($val) { $this->postcode = $val; } 
    function setCity($val) { $this->city = $val; } 
    function setEmail($val) { $this->email = $val; } 
    function setPhone($val) { $this->phone = $val; } 
    function setCountrykey($val) { $this->countrykey = $val; } 
}

