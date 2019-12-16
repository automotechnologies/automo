<?php

namespace Cocorico\PageBundle\Controller\Frontend;

use Cocorico\PageBundle\Repository\PageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Page frontend controller.
 *
 * @Route("/page")
 */
class PageController extends Controller
{

    /**
     * show page depending upon the slug available.
     *
     * @Route("/{slug}", name="cocorico_page_show")
     *
     * @Method("GET")
     *
     * @param  Request $request
     * @param  string  $slug
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws NonUniqueResultException
     */
    public function showAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PageRepository $page */
        $page = $em->getRepository('CocoricoPageBundle:Page')->findOneBySlug(
            $slug,
            $request->getLocale()
        );
        if (!$page) {
            throw new NotFoundHttpException(sprintf('%s page not found.', $slug));
        }

        return $this->render('@CocoricoPage/Frontend/Page/show.html.twig', ['page' => $page]);

    }
}
