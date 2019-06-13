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
     * @Route(name="cocorico_home")
     * @param Request $request
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
            ]
        );
    }


    /**
     *
     * @return Response
     */
    public function rssFeedsAction()
    {
        $feed = $this->getParameter('cocorico.home_rss_feed');
        if (!$feed) {
            return new Response();
        }

        $cacheTime = 3600 * 12;
        $cacheDir = $this->getParameter('kernel.cache_dir');
        $cacheFile = $cacheDir . '/rss-home-feed.json';
        $timeDif = @(time() - filemtime($cacheFile));
        $renderFeeds = [];

        if (file_exists($cacheFile) && $timeDif < $cacheTime) {
            $renderFeeds = json_decode(@file_get_contents($cacheFile), true);
        } else {
            $content = @file_get_contents($feed);
            $feeds = [];

            if ($content) {
                try {
                    $feeds = new \SimpleXMLElement($content);
                    $feeds = $feeds->channel->xpath('//item');
                } catch (\Exception $e) {
                    // silently fail error
                }
            }

            $uploadsDir = $this->getParameter('kernel.project_dir') . "/web/uploads/blog-news";

            /**
             * @var                    $key
             * @var  \SimpleXMLElement $feed
             */
            foreach ($feeds as $key => $feed) {
                $mediaArray = $feed->xpath('media:thumbnail');
                $media = end($mediaArray);
                $renderFeeds[$key]['title'] = (string)$feed->children()->title;
                $renderFeeds[$key]['pubDate'] = (string)$feed->children()->pubDate;
                $renderFeeds[$key]['link'] = (string)$feed->children()->link;
                $imageUrl = end($media->attributes()->url);
                $pathInfo = pathinfo($imageUrl);
                $imageName = uniqid() . "." . $pathInfo['extension'];
                $imageLocal = $uploadsDir .'/'. $imageName;
                file_put_contents($imageLocal, file_get_contents($imageUrl));
                $renderFeeds[$key]['image'] = "/uploads/blog-news/" . $imageName;
                if ($key === 4)
                    break;
            }

            @file_put_contents($cacheFile, json_encode($renderFeeds));
        }


        return $this->render(
            'CocoricoCoreBundle:Frontend/Home:rss_feed.html.twig',
            [
                'feeds' => $renderFeeds,
            ]
        );
    }

}
