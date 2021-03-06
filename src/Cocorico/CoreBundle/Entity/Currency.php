<?php

namespace Cocorico\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="currency")
 * @ORM\Entity()
 */
class Currency
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @ORM\Column(name="code",type="string", length=3, nullable=false, unique=true)
     *
     * @var string
     */
    protected $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="numeric")
     *
     * @ORM\Column(name="rate", type="decimal", length=150, nullable=false, precision=13, scale=7)
     *
     * @var string
     */
    protected $rate;

    /**
     * Get ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set rate
     *
     * @param string $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * Convert currency rate
     *
     * @param float $rate
     */
    public function convert($rate)
    {
        $this->rate /= $rate;
    }
}
