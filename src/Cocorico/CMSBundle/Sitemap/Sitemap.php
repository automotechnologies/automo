<?php


namespace Cocorico\CMSBundle\Sitemap;

use Cocorico\CoreBundle\Entity\ListingImage;
use Cocorico\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Sitemap
{
    private $em;
    private $cache;
    private $router;
    private $manager;

    public function __construct(EntityManager $em, AdapterInterface $cache, Router $router, CacheManager $manager)
    {
        $this->em = $em;
        $this->cache = $cache;
        $this->router = $router;
        $this->manager = $manager;
    }

    public function getSitemapXml(string $lang)
    {
        /** @var $siteMapItem CacheItem */
        $siteMapItem = $this->cache->getItem('sitemap-' . $lang);
        $cacheTime = 172800; // 3600 * 48;

        $urls = [];

        // add static urls
        $urls[] = ['loc' => $this->router->generate('cocorico_home', [], UrlGeneratorInterface::ABSOLUTE_URL)];
        $listings = $this->em->getRepository('CocoricoCoreBundle:Listing')->findAll();
        $pages = $this->em->getRepository('CocoricoPageBundle:Page')->findAll();
        $users = $this->em->getRepository('CocoricoUserBundle:User')->findAllEnabledForSitemap();

        foreach ($pages as $page) {
            $urls[] = [
                'loc' => $this->router->generate('cocorico_page_show', ['slug' => $page->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'priority' => '0.7',
                'lastmod' => $page->getUpdatedAt(),
                'changefreq' => 'monthly'
            ];
        }

        /** @var User $user */
        foreach ($users as $user) {
            $image = $user['imageName'] ?? 'default-user.png';
            $url = '/uploads/users/images/' . $image;
            $url = $this->manager->getBrowserPath($url, 'user_profile');

            $urls[] = [
                'loc' => $this->router->generate('cocorico_user_profile_show', ['id' => $user['id']],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'priority' => '0.5',
                'lastmod' => $user['updatedAt'],
                'changefreq' => 'monthly',
                'image' => [
                    'loc' => $url,
                    'title' => $user['fullName'],
                ],
            ];
        }

        foreach ($listings as $listing) {

            /** @var $listingImage ListingImage */
            $listingImage = $listing->getImages()->get(0);
            $url = $this->manager->getBrowserPath($listingImage->getWebPath(), 'listing_large');

            $urls[] = [
                'loc' => $this->router->generate('cocorico_listing_show', ['slug' => $listing->getSlug()],
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
        $this->cache->save($siteMapItem);

        return $siteMapItem->get();
    }
}