<?php

namespace Cocorico\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ChargeAdmin extends AbstractAdmin
{
    protected $translationDomain = 'SonataAdminBundle';
    protected $baseRoutePattern = 'charge';
    protected $locales;

    // setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'position'
    );

    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    /** @inheritdoc */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->with('Charge')
            ->add('booking', null, ['disabled' => true])
            ->add('chargeId', null, ['disabled' => true])
            ->add('object', null, ['disabled' => true])
            ->add('amount', null, ['disabled' => true])
            ->add('amount_refunded', null, ['disabled' => true])
            ->add('application', null, ['disabled' => true])
            ->add('application_fee', null, ['disabled' => true])
            ->add('application_fee_amount', null, ['disabled' => true])
            ->add('balance_transaction', null, ['disabled' => true])
            ->add('created', null, ['disabled' => true])
            ->add('currency', null, ['disabled' => true])
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
            ->add('id')
            ->add('booking')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('id')
            ->add('booking')
            ->add('chargeId')
            ->add('object')
            ->add('amount')
            ->add('amount_refunded')
            ->add('application')
            ->add('application_fee')
            ->add('application_fee_amount')
            ->add('balance_transaction')
            ->add('created')
            ->add('currency')
        ;

        $listMapper->add(
            '_action',
            'actions',
            array(
                'actions' => array(
                    'edit' => array(),
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

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }
}