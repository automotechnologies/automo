<?php

namespace Cocorico\CoreBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Lexik\Bundle\CurrencyBundle\Entity\Currency;

class CurrencyFixtures extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setCode('EUR');
        $currency->setRate(1.0000);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('USD');
        $currency->setRate(1.2448);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('IDR');
        $currency->setRate(14271.50);
        $manager->persist($currency);

        $manager->flush();
    }

}
