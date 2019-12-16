<?php

namespace Cocorico\CoreBundle\DataFixtures\ORM;

use Cocorico\CoreBundle\Entity\ListingCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ListingCategoryFixtures extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $category = new ListingCategory();
        $category->translate('en')->setName('Category1');
        $category->translate('id')->setName('Kategori1');

        $subCategory1 = new ListingCategory();
        $subCategory1->translate('en')->setName('Category1_1');
        $subCategory1->translate('id')->setName('Kategori1_1');
        $subCategory1->setParent($category);

        $subCategory2 = new ListingCategory();
        $subCategory2->translate('en')->setName('Category1_2');
        $subCategory2->translate('id')->setName('Kategori1_2');
        $subCategory2->setParent($category);


        $manager->persist($category);
        $manager->persist($subCategory1);
        $manager->persist($subCategory2);
        $category->mergeNewTranslations();
        $subCategory1->mergeNewTranslations();
        $subCategory2->mergeNewTranslations();
        $manager->flush();
        $this->addReference('category1_1', $subCategory1);

        $category = new ListingCategory();
        $category->translate('en')->setName('Category2');
        $category->translate('id')->setName('Kategori2');

        $subCategory1 = new ListingCategory();
        $subCategory1->translate('en')->setName('Category2_1');
        $subCategory1->translate('id')->setName('Kategori2_1');
        $subCategory1->setParent($category);

        $subSubCategory1 = new ListingCategory();
        $subSubCategory1->translate('en')->setName('Category2_1_1');
        $subSubCategory1->translate('id')->setName('Kategori2_1_1');
        $subSubCategory1->setParent($subCategory1);

        $subCategory2 = new ListingCategory();
        $subCategory2->translate('en')->setName('Category2_2');
        $subCategory2->translate('id')->setName('Kategori2_2');
        $subCategory2->setParent($category);


        $manager->persist($category);
        $manager->persist($subCategory1);
        $manager->persist($subSubCategory1);
        $manager->persist($subCategory2);

        $category->mergeNewTranslations();
        $subCategory1->mergeNewTranslations();
        $subSubCategory1->mergeNewTranslations();
        $subCategory2->mergeNewTranslations();
        $manager->flush();

    }

}
