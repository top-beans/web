<?php

namespace Application\API\Canonicals\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Countries
 *
 * @ORM\Table(name="Countries")
 * @ORM\Entity
 */
class Country
{
    /**
     * @var integer
     *
     * @ORM\Column(name="countryKey", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countrykey;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
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
