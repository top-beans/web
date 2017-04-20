<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customers
 *
 * @ORM\Table(name="Customers")
 * @ORM\Entity
 */
class Customer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="customerKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $customerkey;

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
     * @ORM\Column(name="username", type="string", length=100, nullable=true)
     */
    private $username;

    function getCustomerkey() { return $this->customerkey; } 
    function getDeliveryaddresskey() { return $this->deliveryaddresskey; } 
    function getBillingaddresskey() { return $this->billingaddresskey; } 
    function getUsername() { return $this->username; } 

    function setCustomerkey($val) { $this->customerkey = $val; } 
    function setDeliveryaddresskey($val) { $this->deliveryaddresskey = $val; } 
    function setBillingaddresskey($val) { $this->billingaddresskey = $val; } 
    function setUsername($val) { $this->username = $val; } 
}