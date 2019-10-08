<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CoreBundle\Controller\Frontend;

use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 *
 */
class HomeController extends Controller
{
    /**
     * @Route(name="cocorico_home", methods={"GET"})
     * @param Request $request
     *
     * @throws
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $listings = $this->get("cocorico.listing_search.manager")->getHighestRanked(
            $this->get('cocorico.listing_search_request'),
            6,
            $request->getLocale()
        );

        $cache = $this->get('cache.app');
        $homePageItem = $cache->getItem('homePage-' . $request->getLocale());

        if ($homePageItem->isHit()) {
            $homeView = $homePageItem->get();
            return new Response($homeView);
        } else {
            $homeView = $this->renderView('CocoricoCoreBundle:Frontend\Home:index.html.twig',
                [
                    'listings' => $listings->getIterator(),
                    'feeds' => $this->getBlogNewsFromCache(),
                ]
            );

            $homePageItem->set($homeView);
            $homePageItem->expiresAfter(10800);
            $cache->save($homePageItem);
        }



        return $this->render('CocoricoCoreBundle:Frontend\Home:index.html.twig',
            [
                'listings' => $listings->getIterator(),
                'feeds' => $this->getBlogNewsFromCache(),
            ]
        );
    }

    /**
     * @return mixed|null
     * @throws InvalidArgumentException
     */
    private function getBlogNewsFromCache()
    {
        $blogNews = $this->get('cache.app')->getItem('blog-news');

        return $blogNews->isHit() ? $blogNews->get() : $this->get('cocorico.blog_news')->getBlogNews();
    }

}
