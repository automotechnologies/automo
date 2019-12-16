<?php

namespace Cocorico\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ListingDiscountType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'discount',
                IntegerType::class,
                array(
                    'label' => 'listing_edit.form.discount',
                    'attr' => array(
                        //todo: use parameters cocorico.listing_discount_xxx instead
                        'min' => '1',
                        'max' => '90',
                    ),
                    'required' => true
                )
            )
            ->add(
                'fromQuantity',
                IntegerType::class,
                array(
                    'label' => 'listing_edit.form.from_quantity',
                    'attr' => array(
                        //todo: use parameters cocorico.listing_discount_xxx instead
                        'min' => '1',
                        'max' => '90',
                    ),
                    'required' => true
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            array(
                'data_class' => 'Cocorico\CoreBundle\Entity\ListingDiscount',
                'translation_domain' => 'cocorico_listing',
                'constraints' => new Valid(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_discount';
    }
}
