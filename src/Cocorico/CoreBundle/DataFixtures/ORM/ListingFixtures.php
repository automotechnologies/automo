<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CoreBundle\DataFixtures\ORM;

use Cocorico\CoreBundle\Entity\Listing;
use Cocorico\CoreBundle\Entity\ListingCategory;
use Cocorico\CoreBundle\Entity\ListingCharacteristic;
use Cocorico\CoreBundle\Entity\ListingCharacteristicValue;
use Cocorico\CoreBundle\Entity\ListingImage;
use Cocorico\CoreBundle\Entity\ListingListingCategory;
use Cocorico\CoreBundle\Entity\ListingListingCharacteristic;
use Cocorico\CoreBundle\Entity\ListingLocation;
use Cocorico\CoreBundle\Entity\ListingTranslation;
use Cocorico\GeoBundle\Entity\Area;
use Cocorico\GeoBundle\Entity\City;
use Cocorico\GeoBundle\Entity\Coordinate;
use Cocorico\GeoBundle\Entity\Country;
use Cocorico\GeoBundle\Entity\Department;
use Cocorico\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ListingFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        //GeoGraphical entities
        $country = new Country();
        $country->setCode("ID");
        $country->translate('en')->setName('Indonesia');
        $country->translate('id')->setName('Indonesia');

        $area = new Area();
        $area->setCountry($country);
        $area->translate('en')->setName('Île-de-Indonesia');
        $area->translate('id')->setName('Île-de-Indonesia');

        $department = new Department();
        $department->setCountry($country);
        $department->setArea($area);
        $department->translate('en')->setName('Jakarta');
        $department->translate('id')->setName('Jakarta');

        $city = new City();
        $city->setCountry($country);
        $city->setArea($area);
        $city->setDepartment($department);
        $city->translate('en')->setName('Jakarta');
        $city->translate('id')->setName('Jakarta');

        $manager->persist($country);
        $manager->persist($area);
        $manager->persist($department);
        $manager->persist($city);
        $country->mergeNewTranslations();
        $area->mergeNewTranslations();
        $department->mergeNewTranslations();
        $city->mergeNewTranslations();

        //Coordinate entity
        $coordinate = new Coordinate();
        $coordinate->setCountry($country);
        $coordinate->setArea($area);
        $coordinate->setDepartment($department);
        $coordinate->setCity($city);
        $coordinate->setZip("");
        $coordinate->setRoute("Jalan H. R. Rasuna Said");
        $coordinate->setStreetNumber("");
        $coordinate->setAddress("Ariobimo Sentral Level 8, Jakarta, Indonesia, 12950");
        $coordinate->setLat(-6.227337);
        $coordinate->setLng(106.833444);
        $manager->persist($coordinate);

        //Listing Location
        $location = new ListingLocation();
        $location->setCountry("ID");
        $location->setCity("Jakarta");
        $location->setZip("");
        $location->setRoute("jalan h. r. rasuna said");
        $location->setStreetNumber("");
        $location->setCoordinate($coordinate);
        $manager->persist($location);

        //Listing Image
        $image1 = new ListingImage();
        $image1->setName(ListingImage::IMAGE_DEFAULT);
        $image1->setPosition(1);

        $image2 = new ListingImage();
        $image2->setName(ListingImage::IMAGE_DEFAULT);
        $image2->setPosition(2);

        //Listing
        $listing = new Listing();
        $listing->setLocation($location);
        $listing->addImage($image1);
        $listing->addImage($image2);
        $listing->translate('en')->setTitle('Listing One');
        $listing->translate('id')->setTitle('Daftar satu');

        $listing->translate('en')->setDescription('Listing One Description');
        $listing->translate('id')->setDescription('Daftar Satu Deskripsi');
        $listing->setStatus(Listing::STATUS_PUBLISHED);
        $listing->setPrice(100000);
        $listing->setCertified(1);

        /** @var User $user */
        $user = $manager->merge($this->getReference('offerer'));
        $listing->setUser($user);

        /** @var ListingCategory $category */
        $category = $manager->merge($this->getReference('category1_1'));
        $listingCategory = new ListingListingCategory();
        $listingCategory->setListing($listing);
        $listingCategory->setCategory($category);
        $listing->addListingListingCategory($listingCategory);

        /** @var ListingCharacteristic $characteristic */
        $characteristic = $manager->merge($this->getReference('characteristic_1'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        /** @var ListingCharacteristicValue $value */
        $value = $manager->merge($this->getReference('characteristic_value_yes'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);


        $characteristic = $manager->merge($this->getReference('characteristic_2'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        $value = $manager->merge($this->getReference('characteristic_value_2'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);


        $characteristic = $manager->merge($this->getReference('characteristic_3'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        $value = $manager->merge($this->getReference('characteristic_value_custom_1'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);


        $characteristic = $manager->merge($this->getReference('characteristic_4'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        $value = $manager->merge($this->getReference('characteristic_value_1'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);

        $manager->persist($listing);
        $listing->mergeNewTranslations();
        $manager->flush();


        /** @var ListingTranslation $translation */
        foreach ($listing->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        $manager->persist($listing);
        $manager->flush();

        $this->addReference('listing-one', $listing);
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ListingCategoryFixtures::class,
            ListingCharacteristicFixtures::class,
            ListingCharacteristicValueFixtures::class,
        );
    }

}
