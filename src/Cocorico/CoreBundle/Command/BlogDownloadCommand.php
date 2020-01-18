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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blogNews = $this->getContainer()->get('cache.app')->getItem('blog-news');

        if (!$blogNews->isHit()) {
            $this->getContainer()->get('cocorico.blog_news')->getBlogNews();
        }
    }
}