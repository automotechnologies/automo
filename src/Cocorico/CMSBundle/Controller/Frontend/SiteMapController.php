<?php


namespace Cocorico\CMSBundle\Controller\Frontend;

use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SiteMapController
 * @package Cocorico\CMSBundle\Controller\Frontend
 *
 */
class SiteMapController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function indexAction(Request $request)
    {
        $lang = $request->getLocale();
        /** @var $siteMapItem CacheItem */
        $siteMapItem = $this->get('cache.app')->getItem('sitemap-' . $lang);

        if ($siteMapItem->isHit()) {
            $urls = $siteMapItem->get();
        } else {
            $urls = $this->get('cocorico.sitemap')->getSitemapXml($lang);
        }

        return $this->render('@CocoricoCMS/Frontend/sitemap.xml.twig', ['urls' => $urls]);
    }
}