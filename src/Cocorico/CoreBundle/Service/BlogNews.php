<?php


namespace Cocorico\CoreBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class BlogNews
{
    private $rssFeed;
    private $projectDir;
    private $cache;
    private $router;

    public function __construct(string $rssFeed, string $projectDir, AdapterInterface $cache, Router $router)
    {
        $this->rssFeed = $rssFeed;
        $this->projectDir = $projectDir;
        $this->cache = $cache;
        $this->router = $router;
    }

    public function getBlogNews()
    {
        $blogNewsItem = $this->cache->getItem('blog-news');
        $content = @file_get_contents($this->rssFeed);
        $feeds = $renderFeeds = [];
        $cacheTime = 3600 * 24;
        $uploadsDir = $this->projectDir . "/web/uploads/blog-news";

        if ($content) {
            $feeds = new \SimpleXMLElement($content);
            $feeds = $feeds->channel->xpath('//item');
        }

        /**
         * @var                    $key
         * @var  \SimpleXMLElement $feed
         */
        foreach ($feeds as $key => $feed) {
            if ('https://blog.otomo.co/2019/05/07/if-theres-something-you-should-spend-your-time-on-during-ramadan-its-this/' == (string)$feed->children()->link) {
                $mediaArray = $feed->xpath('media:content');
                $media = $mediaArray[2];
            } else {
                $mediaArray = $feed->xpath('media:thumbnail');
                $media = end($mediaArray);
            }

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

        $blogNewsItem->set($renderFeeds);
        $blogNewsItem->expiresAfter($cacheTime);
        $this->cache->save($blogNewsItem);

        return $blogNewsItem->get();
    }
}