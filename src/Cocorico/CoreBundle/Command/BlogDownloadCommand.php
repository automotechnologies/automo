<?php


namespace Cocorico\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlogDownloadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cocorico:blog:download')
            ->setDescription('Download blog from https://blog.otomo.co/feed/.')
            ->addOption(
                'Download blog',
                null,
                InputOption::VALUE_OPTIONAL,
                'Download blog from https://blog.otomo.co/feed/ every 6 hours (6h).'
            )
            ->setHelp("Usage php app/console cocorico:blog:download");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $feed = $this->getContainer()->getParameter('cocorico.home_rss_feed');
        $cacheTime = 3600 * 6;
//        $cacheTime = 12;
        $cacheDir = $this->getContainer()->getParameter('kernel.cache_dir');
        $cacheFile = $cacheDir . '/rss-home-feed.json';
        $timeDif = @(time() - filemtime($cacheFile));
        $renderFeeds = [];

        if (!file_exists($cacheFile) || $timeDif >= $cacheTime) {
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

            $uploadsDir = $this->getContainer()->getParameter('kernel.project_dir') . "/web/uploads/blog-news";

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

                if ($key === 1)
                    break;
            }

            @file_put_contents($cacheFile, json_encode($renderFeeds));
        }
    }
}