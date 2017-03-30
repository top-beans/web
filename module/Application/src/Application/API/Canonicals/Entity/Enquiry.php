<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Enquiries")
 * @ORM\Entity
 */
class Enquiry
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="enquiryKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $enquirykey;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    
    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000, nullable=false)
     */
    private $description;
    
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
     * @ORM\Column(name="number", type="string", length=100, nullable=true)
     */
    private $number;
    
    /**
     * @Type("DateTime<'Y-m-d\TH:i:sO'>")
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetime", nullable=false)
     */
    private $createddate;

    function getEnquirykey () { return $this->enquirykey; } 
    function getName() { return $this->name; } 
    function getDescription() { return $this->description; } 
    function getEmail() { return $this->email; } 
    function getNumber() { return $this->number; } 
    function getCreateddate() { return $this->createddate; } 

    function setEnquirykey ($val) { $this->enquirykey  = $val; } 
    function setName($val) { $this->name = $val; } 
    function setDescription($val) { $this->description = $val; } 
    function setEmail($val) { $this->email = $val; } 
    function setNumber($val) { $this->number = $val; } 
    function setCreateddate($val) { $this->createddate = $val; } 
}
