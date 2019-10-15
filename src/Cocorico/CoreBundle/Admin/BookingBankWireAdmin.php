<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt'
    );

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
                array(
                    'query' => $offererQuery,
                    'disabled' => true,
                    'label' => 'admin.booking.offerer.label'
                )
            )
            ->add(
                'booking',
                null,
                array(
                    'disabled' => true,
                    'label' => 'admin.booking.label',
                )
            )->add(
                'booking.status',
                ChoiceType::class,
                array(
                    'disabled' => true,
                    'choices' => array_flip(Booking::$statusValues),
                    'placeholder' => 'admin.booking.status.label',
                    'label' => 'admin.booking_bank_wire.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                )
            );

        $formMapper
            ->add(
                'amount',
                PriceType::class,
                array(
                    'disabled' => true,
                    'label' => 'admin.booking_bank_wire.amount.label',
                    'include_vat' => true,
                    'scale' => 2,
                )
            );

        $statusDisabled = true;
        if ($bookingBankWire && $bookingBankWire->getStatus() == BookingBankWire::STATUS_TO_DO) {
            $statusDisabled = false;
        }

        $formMapper
            ->add(
                'status',
                ChoiceType::class,
                array(
                    'choices' => array_flip(BookingBankWire::$statusValues),
                    'placeholder' => 'admin.booking.status.label',
                    'label' => 'admin.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                    'help' => 'admin.booking_bank_wire.status.help',
                    'disabled' => $statusDisabled
                )
            )
            ->add(
                'payedAt',
                null,
                array(
                    'disabled' => true,
                    'label' => 'admin.booking_bank_wire.payed_at.label',
                    'view_timezone' => $this->timezone
                )
            )
            ->add(
                'createdAt',
                null,
                array(
                    'disabled' => true,
                    'label' => 'admin.booking.created_at.label',
                    'view_timezone' => $this->timezone
                )
            )
            ->add(
                'updatedAt',
                null,
                array(
                    'disabled' => true,
                    'label' => 'admin.booking.updated_at.label',
                    'view_timezone' => $this->timezone
                )
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
                array(),
                ChoiceType::class,
                array(
                    'choices' => array_flip(BookingBankWire::$statusValues),
                    'label' => 'admin.booking.status.label',
                    'translation_domain' => 'cocorico_booking',
                )
            )
            ->add(
                'booking.id',
                null,
                array('label' => 'admin.booking_bank_wire.booking_id.label')
            )
            ->add(
                'user.email',
                null,
                array('label' => 'admin.booking.offerer.label')
            )
            ->add(
                'createdAt',
                'doctrine_orm_callback',
                array(
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
                    'field_options' => array('format' => 'dd/MM/yyyy'),
                ),
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
                array(
                    'label' => 'admin.booking_bank_wire.status.label',
                    'template' => 'CocoricoSonataAdminBundle::list_field_value_translated.html.twig',
                    'data_trans' => 'cocorico_booking'
                )
            )
            ->add(
                'booking',
                null,
                array(
                    'label' => 'admin.booking_bank_wire.booking.label'
                )
            )
            ->add(
                'booking.statusText',
                null,
                array(
                    'label' => 'admin.booking.status.label',
                    'template' => 'CocoricoSonataAdminBundle::list_field_value_translated.html.twig',
                    'data_trans' => 'cocorico_booking'
                )
            )
            ->add(
                'user',
                null,
                array(
                    'label' => 'admin.booking.offerer.label',
                )
            )
            ->add(
                'booking.listing',
                null,
                array(
                    'label' => 'admin.listing.label'
                )
            )
            ->add(
                'booking.start',
                'date',
                array(
                    'label' => 'admin.booking.start.label',
                )
            )
            ->add(
                'booking.end',
                'date',
                array(
                    'label' => 'admin.booking.end.label',
                )
            )
            ->add(
                'booking.amountToPayByAskerDecimal',
                null,
                array(
                    'label' => 'admin.booking.amount.label'
                )
            )
            ->add(
                'amountDecimal',
                null,
                array(
                    /** @Ignore */
                    'label' => 'admin.booking_bank_wire.amount.label'
                )
            );

        $listMapper->add(
            '_action',
            'actions',
            array(
                'actions' => array(
                    'edit' => array(),
//                    'do_bank_wire' => array(
//                        'template' => 'CocoricoMangoPayBundle::Admin/list_action_do_bank_wire.html.twig'
//                    )
                )
            )
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
        $fields = array(
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
        );

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
