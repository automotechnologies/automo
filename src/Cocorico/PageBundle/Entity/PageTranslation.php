<?php

namespace Cocorico\PageBundle\Entity;

use Cocorico\PageBundle\Model\BasePageTranslation;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="page_translation",indexes={
 *    @ORM\Index(name="slug_pt_idx", columns={"slug"})
 *  })
 *
 */
class PageTranslation extends BasePageTranslation
{
    use ORMBehaviors\Translatable\Translation;
    use ORMBehaviors\Sluggable\Sluggable;

}
