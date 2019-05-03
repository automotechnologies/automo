<?php


namespace Cocorico\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PaymentProduct
 * @package Cocorico\CoreBundle\Entity
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class StripeProduct
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", nullable=true, options={"comment": "Type of Object"})
     */
    private $object;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    /**
     * @var array
     *
     * @ORM\Column(name="attributes", type="array", nullable=true)
     */
    private $attributes;

    /**
     * @var string
     * @ORM\Column(name="caption", type="string", nullable=true)
     */
    private $caption;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var array
     *
     * @ORM\Column(name="deactivate_on", type="array", nullable=true)
     */
    private $deactivate_on;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var array
     */
    private $images;

    /**
     * @var
     */
    private $livemode;

    private $name;

    private $package_dimensions;

    private $shippable;

    private $skus;

    private $statement_descriptor;

    private $type;

    private $unit_label;

    private $updated;

    private $url;
}