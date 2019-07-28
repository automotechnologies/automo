<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CMSBundle\Controller\Frontend;

use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Footer frontend controller.
 *
 * @Route("/footer")
 */
class FooterController extends Controller
{
    /**
     * Display footer links
     *
     * @param  Request $request
     *
     * @return Response
     * @throws InvalidArgumentException
     */
    public function indexAction(Request $request)
    {
        return $this->render('@CocoricoCMS/Frontend/Footer/index.html.twig', [
            'footers' => $this->getFooter($request->getLocale())
        ]);

    }

    /**
     * @param string $locale
     * @return array|mixed|null
     * @throws InvalidArgumentException
     */
    private function getFooter(string $locale)
    {
        $cache = $this->get('cache.app');
        $footerItem = $cache->getItem('footer-item-' . $locale);

        if ($footerItem->isHit()) {
            return $footerItem->get();
        } else {
            $footers = $this->getDoctrine()->getRepository('CocoricoCMSBundle:Footer')->findByHash($locale);
        }

        $footerItem->set($footers);
        $footerItem->expiresAfter(345600);
        $cache->save($footerItem);

        return $footers;
    }
}
