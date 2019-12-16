<?php

namespace Cocorico\UserBundle\Controller\Admin;

use Cocorico\UserBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserAdminController extends BaseController
{
    /**
     * Reset user fees as asker and offerer
     *
     * @param ProxyQueryInterface $selectedModelQuery
     * @param Request             $request
     *
     * @return RedirectResponse
     */
    public function batchActionResetFees(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();

        try {
            /** @var User $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setFeeAsAsker(null);
                $selectedModel->setFeeAsOfferer(null);
            }
            $modelManager->update($selectedModel);
        } catch (\Exception $e) {
            $this->get('translator')->trans(
                'flash_batch_reset_fees_error',
                array(),
                'SonataAdminBundle'
            );

            return new RedirectResponse(
                $this->admin->generateUrl('list', $this->admin->getFilterParameters())
            );
        }

        $this->addFlash(
            'sonata_flash_success',
            $this->get('translator')->trans(
                'flash_batch_reset_fees_success',
                array(),
                'SonataAdminBundle'
            )
        );

        return new RedirectResponse(
            $this->admin->generateUrl('list', $this->admin->getFilterParameters())
        );
    }

}