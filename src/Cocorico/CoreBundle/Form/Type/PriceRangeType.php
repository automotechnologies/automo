<?php

namespace Cocorico\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PriceRangeType extends AbstractType
{
    protected $session;
    protected $currency;

    public function __construct(Session $session, $currency)
    {
        $this->session = $session;
        $this->currency = $this->session->get('currency', $currency);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'min',
                PriceType::class,
                array(
                    'label' => 'listing.form.price',
                    'currency' => $this->currency,
                    'scale' => 0
                )
            )
            ->add(
                'max',
                PriceType::class,
                array(
                    /** @Ignore */
                    'label' => false,
                    'currency' => $this->currency,
                    'scale' => 0
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'cocorico_listing',
                'data_class' => 'Cocorico\CoreBundle\Model\PriceRange',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'price_range';
    }
}
