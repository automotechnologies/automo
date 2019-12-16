<?php

namespace Cocorico\CMSBundle\Entity;

use Cocorico\CMSBundle\Model\BaseFooterTranslation;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="footer_translation",indexes={
 *    @ORM\Index(name="footer_url_hash_idx", columns={"url_hash"})
 *  })
 *
 */
class FooterTranslation extends BaseFooterTranslation
{
    use ORMBehaviors\Translatable\Translation;

}
