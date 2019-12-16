<?php

namespace Cocorico\GeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CountryTranslation
 *
 * @ORM\Table(name="geo_country_translation")
 * @ORM\Entity
 */
class CountryTranslation
{
    use ORMBehaviors\Translatable\Translation;
    use ORMBehaviors\Sluggable\Sluggable;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    protected $name;

    public function __construct()
    {

    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getSluggableFields()
    {
        return ['name', 'id'];
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
