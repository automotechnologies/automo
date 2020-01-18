<?php

namespace Cocorico\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Calendar update alert commands
 * Every Month on 27  :
 */

//Cron: 0 0 27 * *  user   php app/console cocorico:listings:alertUpdateCalendars

class AlertListingsCalendarUpdateCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this
            ->setName('cocorico:listings:alertUpdateCalendars')
            ->setDescription('Alert listings calendars update.')
            ->setHelp("Usage php app/console cocorico:listings:alertUpdateCalendars");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->getContainer()->get('cocorico.listing.manager')->alertUpdateCalendars();
        $output->writeln($result . " listing(s) calendar update alerted");
    }

}
