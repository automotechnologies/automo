<?php

namespace Cocorico\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogoImageCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('cocorico:logo-image')
            ->setDescription('')
            ->setHelp("Usage php bin/console cocorico:logo-image");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $listings = $em->getRepository('CocoricoCoreBundle:Listing')->getImages();

        foreach ($listings as $listing) {
            $url = $this->getContainer()->get('liip_imagine.cache.manager')->getBrowserPath('uploads/listings/images/' . $listing['name'], 'listing_large', array(), null);
            $output->writeln("<info>$url</info>", 1);
        }

    }
}