<?php

namespace Cocorico\CoreBundle\DataFixtures\ORM;

use Cocorico\CoreBundle\Entity\ListingCharacteristicGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ListingCharacteristicGroupFixtures extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $listingCharacteristicGroup = new ListingCharacteristicGroup();
        $listingCharacteristicGroup->setPosition(1);
        $listingCharacteristicGroup->translate('en')->setName('Group_1');
        $listingCharacteristicGroup->translate('id')->setName('Kelompok_1');
        $manager->persist($listingCharacteristicGroup);
        $listingCharacteristicGroup->mergeNewTranslations();
        $manager->flush();
        $this->addReference('group_1', $listingCharacteristicGroup);

        $listingCharacteristicGroup = new ListingCharacteristicGroup();
        $listingCharacteristicGroup->setPosition(2);
        $listingCharacteristicGroup->translate('en')->setName('Group_2');
        $listingCharacteristicGroup->translate('id')->setName('Kelompok_2');
        $manager->persist($listingCharacteristicGroup);
        $listingCharacteristicGroup->mergeNewTranslations();
        $manager->flush();
        $this->addReference('group_2', $listingCharacteristicGroup);

    }

}
