<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\Type;

/**
 * @AccessType("public_method")
 * @ORM\Table(name="Countries")
 * @ORM\Entity
 */
class Country
{
    /**
     * @Type("integer")
     * @var integer
     *
     * @ORM\Column(name="countryKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countrykey;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @Type("string")
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    function getCountrykey() { return $this->countrykey; } 
    function getName() { return $this->name; } 
    function getCode() { return $this->code; } 
    
    function setCountrykey($val) { $this->countrykey = $val; } 
    function setName($val) { $this->name = $val; } 
    function setCode($val) { $this->code = $val; } 
}
