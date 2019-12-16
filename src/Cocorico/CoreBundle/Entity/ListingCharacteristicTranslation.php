<?php

namespace Cocorico\CoreBundle\Entity;

use Cocorico\CoreBundle\Model\BaseListingCharacteristicTranslation;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ListingCharacteristicTranslation
 *
 * @ORM\Entity
 *
 * @ORM\Table(name="listing_characteristic_translation")
 */
class ListingCharacteristicTranslation extends BaseListingCharacteristicTranslation
{
    use ORMBehaviors\Translatable\Translation;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
