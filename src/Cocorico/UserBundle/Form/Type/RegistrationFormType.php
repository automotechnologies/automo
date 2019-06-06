<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\UserBundle\Form\Type;

use Cocorico\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationFormType.
 */
class RegistrationFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = ["ID", "GB", "FR", "ES", "DE", "IT", "CH", "US", "RU"];

        $builder
            ->add(
                'personType',
                ChoiceType::class,
                [
                    'label' => 'form.person_type',
                    'choices' => array_flip(User::$personTypeValues),
                    'expanded' => true,
                    'empty_data' => User::PERSON_TYPE_NATURAL,
                    'required' => true,
                ]
            )
            ->add(
                'companyName',
                TextType::class,
                [
                    'label' => 'form.company_name',
                    'required' => false,
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                ['label' => 'form.last_name']
            )
            ->add(
                'firstName',
                TextType::class,
                ['label' => 'form.first_name']
            )
            ->add(
                'phonePrefix',
                TextType::class,
                [
                    'label' => 'form.user.phone_prefix',
                    'required' => false,
                    'empty_data' => '+33',
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => 'form.user.phone',
                    'required' => false,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                ['label' => 'form.email']
            )
            ->add(
                'birthday',
                BirthdayType::class,
                [
                    'label' => 'form.user.birthday',
                    'widget' => 'choice',
                    'years' => range(date('Y') - 18, date('Y') - 100),
                    'required' => true,
                ]
            )
            ->add(
                'nationality',
                CountryType::class,
                [
                    'label' => 'form.user.nationality',
                    'required' => true,
                    'preferred_choices' => $countries,
                ]
            )
            ->add(
                'countryOfResidence',
                CountryType::class,
                [
                    'label' => 'form.user.country_of_residence',
                    'required' => true,
                    'preferred_choices' => $countries,
                    'data' => 'ID',
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => 'password',
                    'options' => ['translation_domain' => 'cocorico_user'],
                    'first_options' => [
                        'label' => 'form.password',
                        'required' => true,
                    ],
                    'second_options' => [
                        'label' => 'form.password_confirmation',
                        'required' => true,
                    ],
                    'invalid_message' => 'fos_user.password.mismatch',
                    'required' => true,
                ]
            )
            ->add(
                'timeZone',
                TimezoneType::class,
                [
                    'label' => 'form.time_zone',
                    'required' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Cocorico\UserBundle\Entity\User',
                'csrf_token_id' => 'user_registration',
                'translation_domain' => 'cocorico_user',
                'validation_groups' => ['CocoricoRegistration'],
            ]
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user_registration';
    }
}
