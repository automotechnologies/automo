<?php

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
