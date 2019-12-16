<?php

namespace Cocorico\CoreBundle\DataFixtures\ORM;

use Cocorico\CoreBundle\Entity\ListingCharacteristic;
use Cocorico\CoreBundle\Entity\ListingCharacteristicGroup;
use Cocorico\CoreBundle\Entity\ListingCharacteristicType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ListingCharacteristicFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $listingCharacteristic = new ListingCharacteristic();
        $listingCharacteristic->setPosition(1);
        $listingCharacteristic->translate('en')->setName('Characteristic_1');
        $listingCharacteristic->translate('id')->setName('Ciri_1');
        $listingCharacteristic->translate('en')->setDescription('Characteristic_1 description');
        $listingCharacteristic->translate('id')->setDescription('Deskripsi karakteristik 1');
        /** @var ListingCharacteristicType $listingCharacteristicType */
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_yes_no'));
        $listingCharacteristic->setListingCharacteristicType($listingCharacteristicType);
        /** @var ListingCharacteristicGroup $listingCharacteristicGroup */
        $listingCharacteristicGroup = $manager->merge($this->getReference('group_1'));
        $listingCharacteristic->setListingCharacteristicGroup($listingCharacteristicGroup);

        $manager->persist($listingCharacteristic);
        $listingCharacteristic->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_1', $listingCharacteristic);


        $listingCharacteristic = new ListingCharacteristic();
        $listingCharacteristic->setPosition(2);
        $listingCharacteristic->translate('en')->setName('Characteristic_2');
        $listingCharacteristic->translate('id')->setName('Karakteristik_2');
        $listingCharacteristic->translate('en')->setDescription('Characteristic_2 description');
        $listingCharacteristic->translate('id')->setDescription('Deskripsi karakteristik 2');
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_quantity'));
        $listingCharacteristic->setListingCharacteristicGroup($listingCharacteristicGroup);
        $listingCharacteristic->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristic);
        $listingCharacteristic->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_2', $listingCharacteristic);

        $listingCharacteristic = new ListingCharacteristic();
        $listingCharacteristic->setPosition(3);
        $listingCharacteristic->translate('en')->setName('Characteristic_3');
        $listingCharacteristic->translate('id')->setName('Karakteristik_3');
        $listingCharacteristic->translate('en')->setDescription('Characteristic_3 description');
        $listingCharacteristic->translate('id')->setDescription('Deskripsi karakteristik 3');
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_custom_1'));
        $listingCharacteristic->setListingCharacteristicType($listingCharacteristicType);
        $listingCharacteristicGroup = $manager->merge($this->getReference('group_2'));
        $listingCharacteristic->setListingCharacteristicGroup($listingCharacteristicGroup);
        $manager->persist($listingCharacteristic);
        $listingCharacteristic->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_3', $listingCharacteristic);


        $listingCharacteristic = new ListingCharacteristic();
        $listingCharacteristic->setPosition(4);
        $listingCharacteristic->translate('en')->setName('Characteristic_4');
        $listingCharacteristic->translate('id')->setName('Karakteristik_4');
        $listingCharacteristic->translate('en')->setDescription('Characteristic_4 description');
        $listingCharacteristic->translate('id')->setDescription('Deskripsi karakteristik 4');
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_custom_1'));
        $listingCharacteristic->setListingCharacteristicType($listingCharacteristicType);
        $listingCharacteristicGroup = $manager->merge($this->getReference('group_2'));
        $listingCharacteristic->setListingCharacteristicGroup($listingCharacteristicGroup);
        $manager->persist($listingCharacteristic);
        $listingCharacteristic->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_4', $listingCharacteristic);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return array(
            ListingCharacteristicTypeFixtures::class,
            ListingCharacteristicGroupFixtures::class,
        );
    }

}
