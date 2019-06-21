<?php


namespace Cocorico\CMSBundle\Controller\Frontend;

use Cocorico\CoreBundle\Entity\ListingImage;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $cacheTime = 172800; // 3600 * 48;

        if ($siteMapItem->isHit()) {
            $urls = $siteMapItem->get();
        } else {

            $em = $this->getDoctrine()->getManager();
            $urls = [];

            // add static urls
            $urls[] = ['loc' => $this->generateUrl('cocorico_home', [], UrlGeneratorInterface::ABSOLUTE_URL)];

            $listings = $em->getRepository('CocoricoCoreBundle:Listing')->findAll();

            $pages = $em->getRepository('CocoricoPageBundle:Page')->findAll();
            $users = $em->getRepository('CocoricoUserBundle:User')->findAllEnabledForSitemap();

            foreach ($pages as $page) {
                $urls[] = [
                    'loc' => $this->generateUrl('cocorico_page_show', ['slug' => $page->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    'priority' => '0.7',
                    'lastmod' => $page->getUpdatedAt(),
                    'changefreq' => 'monthly'
                ];
            }

            foreach ($users as $user) {
                $urls[] = [
                    'loc' => $this->generateUrl('cocorico_user_profile_show', ['id' => $user['id']],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    'priority' => '0.5',
                    'lastmod' => $user['updatedAt'],
                    'changefreq' => 'monthly'
                ];
            }

            foreach ($listings as $listing) {

                /** @var $listingImage ListingImage */
                $listingImage = $listing->getImages()->get(0);
                $url = $this->container->get('liip_imagine.cache.manager')->getBrowserPath($listingImage->getWebPath(), 'listing_large');

                $urls[] = [
                    'loc' => $this->generateUrl('cocorico_listing_show', ['slug' => $listing->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    'priority' => '0.7',
                    'lastmod' => $listing->getUpdatedAt(),
                    'changefreq' => 'weekly',
                    'image' => [
                        'loc' => $url,
                        'title' => $listing->getTitle(),
                    ]
                ];
            }

            $siteMapItem->set($urls);
            $siteMapItem->expiresAfter($cacheTime);
            $this->container->get('cache.app')->save($siteMapItem);
        }

        return $this->render('@CocoricoCMS/Frontend/sitemap.xml.twig', ['urls' => $urls]);
    }
}