<?php

namespace Cocorico\CoreBundle\Form\Type\Dashboard;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ListingEditCategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

//        $builder
//            ->add(
//                'type',
//                ChoiceType::class,
//                array(
//                    'choices' => array_flip(Listing::$typeValues),
//                    'placeholder' => 'listing_edit.form.choose',
//                    'required' => false,
//                    'translation_domain' => 'cocorico_listing',
//                    'label' => 'listing.form.type',
//                )
//            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            array(
                'data_class' => 'Cocorico\CoreBundle\Entity\Listing',
                'csrf_token_id' => 'listing_edit',
                'translation_domain' => 'cocorico_listing',
                'constraints' => new Valid(),//To have error on collection item field,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_edit_categories';
    }

}
