<?php

namespace Cocorico\CoreBundle\Form\Type\Dashboard;

use Cocorico\CoreBundle\Form\Type\PriceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingEditAvailabilityPriceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'price',
                PriceType::class,
                array(
                    'label' => 'listing_edit.form.price_custom',
                )
            );

        //Set default status for new availability
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) {
//                /** @var ListingAvailability $availability */
//                $availability = $event->getData();
//                $form = $event->getForm();
//
//                if ((!$availability || null === $availability->getId())) {
//                    $form->add('status', 'hidden');
//                    if ($availability) {
//                        $availability->setStatus(ListingAvailability::STATUS_AVAILABLE);
//                    }
//                    $event->setData($availability);
//                }
//            }
//        );
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'cocorico_listing'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_edit_availability_price';
    }
}
