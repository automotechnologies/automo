<?php

namespace Cocorico\CoreBundle\Model;

use Cocorico\CoreBundle\Validator\Constraints as CocoricoAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BaseListingDiscount
 *
 * @CocoricoAssert\ListingDiscount()
 *
 * @ORM\MappedSuperclass
 *
 */
abstract class BaseListingDiscount
{

    /**
     * @ORM\Column(name="discount", type="smallint", nullable=false)
     * @Assert\NotBlank(message="assert.not_blank")
     *
     * @var int
     */
    protected $discount;

    /**
     * By default it's the number of time unit
     *
     * @ORM\Column(name="from_quantity", type="smallint", nullable=false)
     * @Assert\NotBlank(message="assert.not_blank")
     *
     * @var int
     */
    protected $fromQuantity;


    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return int
     */
    public function getFromQuantity()
    {
        return $this->fromQuantity;
    }

    /**
     * @param int $fromQuantity
     */
    public function setFromQuantity($fromQuantity)
    {
        $this->fromQuantity = $fromQuantity;
    }


}
