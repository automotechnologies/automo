<?php

namespace Cocorico\BreadcrumbBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BreadcrumbsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $breadcrumbsManager = $this->get('cocorico.breadcrumbs_manager');
        $breadcrumbsManager->addItemsFromYAML($request, trim($request->get('_route')));

        return $this->render(
            'CocoricoBreadcrumbBundle:Breadcrumbs:index.html.twig'
        );
    }
}
