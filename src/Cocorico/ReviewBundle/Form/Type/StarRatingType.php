<?php

namespace Cocorico\ReviewBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Star Rating type for the rating, extended from the choices
 *
 */
class StarRatingType extends AbstractType
{

    /**
     * getParent returns the parent type which will be overriding
     *
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'star_rating';
    }
}