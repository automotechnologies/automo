<?php

namespace Cocorico\CoreBundle\DataFixtures\ORM;

use Cocorico\CoreBundle\Entity\ListingCharacteristicType;
use Cocorico\CoreBundle\Entity\ListingCharacteristicValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ListingCharacteristicValueFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->translate('en')->setName('Yes');
        $listingCharacteristicValue->translate('id')->setName('Ya');
        $listingCharacteristicValue->setPosition(1);
        /** @var ListingCharacteristicType $listingCharacteristicType */
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_yes_no'));
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_yes', $listingCharacteristicValue);

        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->translate('en')->setName('No');
        $listingCharacteristicValue->translate('id')->setName('Tidak');
        $listingCharacteristicValue->setPosition(2);
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_no', $listingCharacteristicValue);

        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->translate('en')->setName('1');
        $listingCharacteristicValue->translate('id')->setName('1');
        $listingCharacteristicValue->setPosition(1);
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_quantity'));
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_1', $listingCharacteristicValue);

        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->translate('en')->setName('2');
        $listingCharacteristicValue->translate('id')->setName('2');
        $listingCharacteristicValue->setPosition(2);
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_2', $listingCharacteristicValue);

        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->translate('en')->setName('3');
        $listingCharacteristicValue->translate('id')->setName('3');
        $listingCharacteristicValue->setPosition(3);
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_3', $listingCharacteristicValue);

        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->translate('en')->setName('Custom value 1');
        $listingCharacteristicValue->translate('id')->setName('Nilai khusus 1');
        $listingCharacteristicValue->setPosition(1);
        $listingCharacteristicType = $manager->merge($this->getReference('characteristic_type_custom_1'));
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_custom_1', $listingCharacteristicValue);

        $listingCharacteristicValue = new ListingCharacteristicValue();
        $listingCharacteristicValue->setName("Custom value 2");
        $listingCharacteristicValue->translate('en')->setName('Custom value 2');
        $listingCharacteristicValue->translate('id')->setName('Nilai khusus 2');
        $listingCharacteristicValue->setPosition(2);
        $listingCharacteristicValue->setListingCharacteristicType($listingCharacteristicType);
        $manager->persist($listingCharacteristicValue);
        $listingCharacteristicValue->mergeNewTranslations();
        $manager->flush();
        $this->addReference('characteristic_value_custom_2', $listingCharacteristicValue);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return array(
            ListingCharacteristicTypeFixtures::class,
        );
    }

}
