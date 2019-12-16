<?php

namespace Cocorico\TimeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeekDaysType extends AbstractType
{

    public function __construct()
    {

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'mapped' => false,
                'choices' => array(
                    'cocorico.monday' => '1',
                    'cocorico.tuesday' => '2',
                    'cocorico.wednesday' => '3',
                    'cocorico.thursday' => '4',
                    'cocorico.friday' => '5',
                    'cocorico.saturday' => '6',
                    'cocorico.sunday' => '7',
                ),
                'translation_domain' => 'cocorico',
                'multiple' => true,
                'expanded' => true,
                /** @Ignore */
                'label' => false,
                'data' => array('1', '2', '3', '4', '5', '6', '7'),
            )
        );
    }

    /**
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
        return 'weekdays';
    }
}
