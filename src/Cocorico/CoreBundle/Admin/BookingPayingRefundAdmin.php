<?php

namespace Cocorico\CoreBundle\Admin;

use Cocorico\CoreBundle\Entity\BookingPayingRefund;
use Cocorico\CoreBundle\Form\Type\PriceType;
use Cocorico\UserBundle\Repository\UserRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookingPayingRefundAdmin extends AbstractAdmin
{
    protected $translationDomain = 'SonataAdminBundle';
    protected $baseRoutePattern = 'booking-paying-refund';
    protected $locales;
    protected $timeUnit;
    protected $timeUnitIsDay;
    protected $timezone;

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

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /** @inheritdoc */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var BookingPayingRefund $bookingPayingRefund */
        $bookingPayingRefund = $this->getSubject();

        $askerQuery = null;
        if ($bookingPayingRefund) {
            /** @var UserRepository $userRepository */
            $userRepository = $this->modelManager->getEntityManager('CocoricoUserBundle:User')
                ->getRepository('CocoricoUserBundle:User');

            $askerQuery = $userRepository->getFindOneQueryBuilder($bookingPayingRefund->getUser()->getId());
        }

        $formMapper
            ->with('admin.booking_payin_refund.title')
            ->add(
                'user',
                'sonata_type_model',
                [
                    'query' => $askerQuery,
                    'disabled' => true,
                    'label' => 'admin.booking.asker.label',
                ]
            )
            ->add(
                'booking',
                null,
                [
                    'disabled' => true,
                    'label' => 'admin.booking.label',
                ]
            )
            ->add(
                'amount',
                PriceType::class,
                [
                    'disabled' => true,
                    'label' => 'admin.booking_payin_refund.amount.label',
                    'include_vat' => true,
                    'scale' => 2,
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'disabled' => true,
                    'choices' => array_flip(BookingPayingRefund::$statusValues),
                    'placeholder' => 'admin.booking.status.label',
                    'label' => 'admin.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                ]
            )
            ->add(
                'payedAt',
                null,
                [
                    'disabled' => true,
                    'label' => 'admin.booking_payin_refund.payed_at.label',
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
                    'view_timezone' => $this->timezone
                ]
            )
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
                    'choices' => array_flip(BookingPayingRefund::$statusValues),
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
                    'label' => 'admin.booking.asker.label',
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
                'booking',
                null,
                [
                    'label' => 'admin.booking_bank_wire.booking.label',
                ]
            )
            ->add(
                'statusText',
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
                    'label' => 'admin.booking.asker.label',
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
                'createdAt',
                null,
                [
                    'label' => 'admin.booking.created_at.label',
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
                    'label' => 'admin.booking_payin_refund.amount.label',
                ]
            )
            ->add(
                'payedAt',
                null,
                [
                    'label' => 'admin.booking_payin_refund.payed_at.label',
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
            'Booking' => 'booking',
            'Status' => 'statusText',
            'User' => 'user',
            'Booking Listing' => 'booking.listing',
            'Booking Start' => 'booking.start',
            'Booking End' => 'booking.end',
            'Created At' => 'createdAt',
            'Booking Amount Pay By Asker' => 'booking.amountToPayByAskerDecimal',
            'Amount' => 'amountDecimal',
            'Payed At' => 'payedAt'
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
