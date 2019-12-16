<?php

namespace Cocorico\TimeBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;


class DateHiddenType extends DateType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'date_hidden';
    }

    public function getParent()
    {
        return 'hidden';
    }
}
