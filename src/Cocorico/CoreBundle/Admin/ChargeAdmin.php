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
            ->add('customer', null, ['disabled' => true])
            ->add('description', null, ['disabled' => true])
            ->add('dispute', null, ['disabled' => true])
            ->add('failure_code', null, ['disabled' => true])
            ->add('failure_message', null, ['disabled' => true])
            ->add('fraud_details', 'choice', [
                'disabled' => true,
                'multiple' => true,
            ])
            ->add('invoice', null, ['disabled' => true])
            ->add('livemode', null, ['disabled' => true])
            ->add('metadata', null, [
                'disabled' => true,
            ])
            ->add('on_behalf_of', null, ['disabled' => true])
//            ->add('stripe_order', null, ['disabled' => true])
            ->add('outcome', null, ['disabled' => true])
            ->add('paid', null, ['disabled' => true])
            ->add('payment_intent', null, ['disabled' => true])
            ->add('payment_method', null, ['disabled' => true])
            ->add('payment_method_details', null, ['disabled' => true])
            ->add('receipt_email', null, ['disabled' => true])
            ->add('receipt_number', null, ['disabled' => true])
            ->add('receipt_url', null, ['disabled' => true])
            ->add('refunded', null, ['disabled' => true])
            ->add('refunds', null, ['disabled' => true])
            ->add('review', null, ['disabled' => true])
            ->add('shipping', null, ['disabled' => true])
            ->add('source', null, ['disabled' => true])
            ->add('source_transfer', null, ['disabled' => true])
            ->add('statement_descriptor', null, ['disabled' => true])
            ->add('status', null, ['disabled' => true])
            ->add('transfer_data', null, ['disabled' => true])
            ->add('transfer_group', null, ['disabled' => true])
            ->end()
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