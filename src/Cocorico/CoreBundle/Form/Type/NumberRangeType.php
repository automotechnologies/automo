<?php

namespace Cocorico\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;


class NumberRangeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'min',
                NumberType::class,
                array_merge(
                    array(
                        'constraints' => array(
                            new Type("numeric")
                        ),
                        'invalid_message' => 'This value should be of type {{ type }}.',
                        'invalid_message_parameters' => array('{{ type }}' => 'numérique'),
                    ),
                    $options['min_options']
                )
            )->add(
                'max',
                NumberType::class,
                array_merge(
                    array(
                        'constraints' => array(
                            new Type("numeric")
                        ),
                        'invalid_message' => 'This value should be of type {{ type }}.',
                        'invalid_message_parameters' => array('{{ type }}' => 'numérique'),
                    ),
                    $options['max_options']
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Cocorico\CoreBundle\Model\NumberRange',
                'min_options' => array(),
                'max_options' => array(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'number_range';
    }
}
