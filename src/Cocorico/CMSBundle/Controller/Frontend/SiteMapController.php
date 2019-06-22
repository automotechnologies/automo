<?php


namespace Cocorico\CMSBundle\Controller\Frontend;

use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SiteMapController
 * @package Cocorico\CMSBundle\Controller\Frontend
 *
 */
class SiteMapController extends Controller
{
    /**
     * @return Response
     * @throws InvalidArgumentException
     */
    public function indexAction()
    {
        /** @var $siteMapItem CacheItem */
        $siteMapItem = $this->get('cache.app')->getItem('sitemap');

        if ($siteMapItem->isHit()) {
            $urls = $siteMapItem->get();
        } else {
            $urls = $this->get('cocorico.sitemap')->getSitemapXml();
        }

        return $this->render('@CocoricoCMS/Frontend/sitemap.xml.twig', ['urls' => $urls]);
    }
}