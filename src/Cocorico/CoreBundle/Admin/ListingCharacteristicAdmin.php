<?php

namespace Cocorico\CoreBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Cocorico\CoreBundle\Entity\ListingCharacteristic;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Validator\Constraints\NotBlank;

class ListingCharacteristicAdmin extends AbstractAdmin
{
    protected $translationDomain = 'SonataAdminBundle';
    protected $baseRoutePattern = 'listing-characteristic';
    protected $locales;

    // setup the default sort column and order
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by' => 'position'
    ];

    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    /** @inheritdoc */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var ListingCharacteristic $subject */
//        $subject = $this->getSubject();

        //Translations fields
        $titles = $descriptions = [];
        foreach ($this->locales as $i => $locale) {
            $titles[$locale] = [
                'label' => 'Name',
                'constraints' => [
                    new NotBlank(),
                ]
            ];
            $descriptions[$locale] = [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(),
                ]
            ];
        }

        $formMapper
            ->with('admin.listing_characteristic.title')
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'locales' => $this->locales,
                    'required_locales' => $this->locales,
                    'fields' => [
                        'name' => [
                            'field_type' => 'text',
                            'locale_options' => $titles,
                        ],
                        'description' => [
                            'field_type' => 'textarea',
                            'locale_options' => $descriptions,
                        ]
                    ],
                    /** @Ignore */
                    'label' => 'Descriptions',
                ]
            )
            ->add(
                'position',
                null,
                [
                    'label' => 'admin.listing_characteristic.position.label',
                ]
            )
            ->add(
                'listingCharacteristicType',
                'sonata_type_model_list',
                [
                    'label' => 'admin.listing_characteristic.type.label',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->add(
                'listingCharacteristicGroup',
                'sonata_type_model_list',
                [
                    'label' => 'admin.listing_characteristic.group.label',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->end();
    }

    /** @inheritdoc */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'translations.name',
                null,
                [
                    'label' => 'admin.listing_characteristic.name.label',
                ]
            )
            ->add(
                'listingCharacteristicType',
                null,
                [
                    'label' => 'admin.listing_characteristic.type.label',
                ]
            )
            ->add(
                'listingCharacteristicGroup',
                null,
                [
                    'label' => 'admin.listing_characteristic.group.label',
                ]
            );
    }

    /** @inheritdoc */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add(
                'name',
                null,
                [
                    'label' => 'admin.listing_characteristic.name.label',
                ]
            )
            ->addIdentifier(
                'listingCharacteristicType',
                null,
                [
                    'label' => 'admin.listing_characteristic.type.label',
                ]
            )
            ->addIdentifier(
                'listingCharacteristicGroup',
                null,
                [
                    'label' => 'admin.listing_characteristic.group.label',
                ]
            )
            ->add(
                'position',
                null,
                [
                    'label' => 'admin.listing_characteristic.position.label',
                ]
            );


        $listMapper->add(
            '_action',
            'actions',
            [
                'actions' => [
//                    'show' => [],
                    'edit' => [],
                ]
            ]
        );
    }

    public function getExportFields()
    {
        return [
            'Id' => 'id',
            'Name' => 'name',
            'Type of Characteristic' => 'listingCharacteristicType',
            'Group' => 'listingCharacteristicGroup',
            'Position' => 'position'
        ];
    }

    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        $dataSourceIt = $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());
        $dataSourceIt->setDateTimeFormat('d M Y'); //change this to suit your needs

        return $dataSourceIt;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions["delete"]);

        return $actions;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        //$collection->remove('create');
        //$collection->remove('delete');
    }
}
