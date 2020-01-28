<?php

namespace Cocorico\CoreBundle\Admin;

use Cocorico\CoreBundle\Entity\Booking;
use Cocorico\CoreBundle\Entity\BookingBankWire;
use Cocorico\CoreBundle\Form\Type\PriceType;
use Cocorico\CoreBundle\Model\Manager\BookingBankWireManager;
use Cocorico\UserBundle\Repository\UserRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookingBankWireAdmin extends AbstractAdmin
{
    protected $translationDomain = 'SonataAdminBundle';
    protected $baseRoutePattern = 'booking-bank-wire';
    protected $locales;
    protected $timeUnit;
    protected $timeUnitIsDay;
    protected $currency;
    /** @var  BookingBankWireManager $bookingBankWireManager */
    protected $bookingBankWireManager;
    protected $timezone;

    // setup the default sort column and order
    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt'
    ];

    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    public function setTimeUnit($timeUnit)
    {
        $this->timeUnit = $timeUnit;
        $this->timeUnitIsDay = ($timeUnit % 1440 == 0) ? true : false;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function setBookingBankWireManager(BookingBankWireManager $bookingBankWireManager)
    {
        $this->bookingBankWireManager = $bookingBankWireManager;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /** @inheritdoc */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var BookingBankWire $bookingBankWire */
        $bookingBankWire = $this->getSubject();

        $offererQuery = null;
        if ($bookingBankWire) {
            /** @var UserRepository $userRepository */
            $userRepository = $this->modelManager->getEntityManager('CocoricoUserBundle:User')
                ->getRepository('CocoricoUserBundle:User');

            $offererQuery = $userRepository->getFindOneQueryBuilder($bookingBankWire->getUser()->getId());
        }


        $formMapper
            ->tab('admin.booking_bank_wire.title')
            ->with('')
            ->add(
                'user',
                'sonata_type_model',
                [
                    'query' => $offererQuery,
                    'disabled' => true,
                    'label' => 'admin.booking.offerer.label',
                ]
            )
            ->add(
                'booking',
                null,
                [
                    'disabled' => true,
                    'label' => 'admin.booking.label',
                ]
            )->add(
                'booking.status',
                ChoiceType::class,
                [
                    'disabled' => true,
                    'choices' => array_flip(Booking::$statusValues),
                    'placeholder' => 'admin.booking.status.label',
                    'label' => 'admin.booking_bank_wire.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                ]
            );

        $formMapper
            ->add(
                'amount',
                PriceType::class,
                [
                    'disabled' => true,
                    'label' => 'admin.booking_bank_wire.amount.label',
                    'include_vat' => true,
                    'scale' => 2,
                ]
            );

        $statusDisabled = true;
        if ($bookingBankWire && $bookingBankWire->getStatus() == BookingBankWire::STATUS_TO_DO) {
            $statusDisabled = false;
        }

        $formMapper
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => array_flip(BookingBankWire::$statusValues),
                    'placeholder' => 'admin.booking.status.label',
                    'label' => 'admin.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                    'help' => 'admin.booking_bank_wire.status.help',
                    'disabled' => $statusDisabled,
                ]
            )
            ->add(
                'payedAt',
                null,
                [
                    'disabled' => true,
                    'label' => 'admin.booking_bank_wire.payed_at.label',
                    'view_timezone' => $this->timezone,
                ]
            )
            ->add(
                'createdAt',
                null,
                [
                    'disabled' => true,
                    'label' => 'admin.booking.created_at.label',
                    'view_timezone' => $this->timezone,
                ]
            )
            ->add(
                'updatedAt',
                null,
                [
                    'disabled' => true,
                    'label' => 'admin.booking.updated_at.label',
                    'view_timezone' => $this->timezone,
                ]
            )
            ->end()
            ->end();
    }

    /** @inheritdoc */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add(
                'status',
                'doctrine_orm_string',
                [],
                ChoiceType::class,
                [
                    'choices' => array_flip(BookingBankWire::$statusValues),
                    'label' => 'admin.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                ]
            )
            ->add(
                'booking.id',
                null,
                [
                    'label' => 'admin.booking_bank_wire.booking_id.label',
                ]
            )
            ->add(
                'user.email',
                null,
                [
                    'label' => 'admin.booking.offerer.label',
                ]
            )
            ->add(
                'createdAt',
                'doctrine_orm_callback',
                [
                    'label' => 'admin.booking.created_at.label',
                    'callback' => function ($queryBuilder, $alias, $field, $value) {
                        /** @var \DateTime $date */
                        $date = $value['value'];
                        if (!$date) {
                            return false;
                        }

                        $queryBuilder
                            ->andWhere("DATE_FORMAT($alias.createdAt,'%Y-%m-%d') = :createdAt")
                            ->setParameter('createdAt', $date->format('Y-m-d'));

                        return true;
                    },
                    'field_type' => 'sonata_type_date_picker',
                    'field_options' => [
                        'format' => 'dd/MM/yyyy',
                    ],
                ],
                null
            );
    }

    /** @inheritdoc */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add(
                'statusText',
                null,
                [
                    'label' => 'admin.booking_bank_wire.status.label',
                    'template' => 'CocoricoSonataAdminBundle::list_field_value_translated.html.twig',
                    'data_trans' => 'cocorico_booking',
                ]
            )
            ->add(
                'booking',
                null,
                [
                    'label' => 'admin.booking_bank_wire.booking.label',
                ]
            )
            ->add(
                'booking.statusText',
                null,
                [
                    'label' => 'admin.booking.status.label',
                    'template' => 'CocoricoSonataAdminBundle::list_field_value_translated.html.twig',
                    'data_trans' => 'cocorico_booking',
                ]
            )
            ->add(
                'user',
                null,
                [
                    'label' => 'admin.booking.offerer.label',
                ]
            )
            ->add(
                'booking.listing',
                null,
                [
                    'label' => 'admin.listing.label',
                ]
            )
            ->add(
                'booking.start',
                'date',
                [
                    'label' => 'admin.booking.start.label',
                ]
            )
            ->add(
                'booking.end',
                'date',
                [
                    'label' => 'admin.booking.end.label',
                ]
            )
            ->add(
                'booking.amountToPayByAskerDecimal',
                null,
                [
                    'label' => 'admin.booking.amount.label',
                ]
            )
            ->add(
                'amountDecimal',
                null,
                [
                    /** @Ignore */
                    'label' => 'admin.booking_bank_wire.amount.label',
                ]
            );

        $listMapper->add(
            '_action',
            'actions',
            [
                'actions' => [
                    'edit' => [],
//                    'do_bank_wire' => [
//                        'template' => 'CocoricoMangoPayBundle::Admin/list_action_do_bank_wire.html.twig'
//                    ]
                ]
            ]
        );
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions["delete"]);

        return $actions;
    }

    public function getExportFields()
    {
        $fields = [
            'Id' => 'id',
            'Status' => 'statusText',
            'Booking' => 'booking',
            'Booking Status' => 'booking.statusText',
            'User' => 'user',
            'Booking Listing' => 'booking.listing',
            'Booking Start' => 'booking.start',
            'Booking End' => 'booking.end',
            'Booking Amount Pay To Offerer' => 'booking.amountToPayToOffererDecimal',
            'Amount' => 'amountDecimal',
        ];

        return $fields;
    }

    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        $dataSourceIt = $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());
        $dataSourceIt->setDateTimeFormat('d M Y');

        return $dataSourceIt;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }
}
