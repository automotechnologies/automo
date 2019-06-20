<?php


namespace Cocorico\CMSBundle\Controller\Frontend;

use Cocorico\CoreBundle\Entity\ListingImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $urls = [];

        // add static urls
        $urls[] = ['loc' => $this->generateUrl('cocorico_home', [], UrlGeneratorInterface::ABSOLUTE_URL)];

        $listings = $em->getRepository('CocoricoCoreBundle:Listing')->findAll();

        $pages = $em->getRepository('CocoricoPageBundle:Page')->findAll();

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

        foreach ($listings as $listing) {

            /** @var $listingImage ListingImage */
            $listingImage = $listing->getImages()->get(0);
            $url = $this->container->get('liip_imagine.cache.manager')->getBrowserPath($listingImage->getWebPath(), 'listing_large');

            $urls[] = [
                'loc' => $this->get('router')->generate(
                    'cocorico_listing_show',
                    ['slug' => $listing->getSlug()],
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

        return $this->render('@CocoricoCMS/Frontend/sitemap.xml.twig', ['urls' => $urls]);
    }
}