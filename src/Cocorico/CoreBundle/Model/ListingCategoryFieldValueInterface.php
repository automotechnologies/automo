<?php

namespace Cocorico\CoreBundle\Model;

use Cocorico\CoreBundle\Entity\ListingListingCategory;

/**
 * ListingCategoryFieldValueInterface
 */
interface ListingCategoryFieldValueInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * @return ListingCategoryListingCategoryFieldInterface
     */
    public function getListingCategoryListingCategoryField();

    /**
     * @param ListingCategoryListingCategoryFieldInterface $field
     */
    public function setListingCategoryListingCategoryField(ListingCategoryListingCategoryFieldInterface $field);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     */
    public function setValue($value = null);

    /**
     * @return ListingListingCategory
     */
    public function getListingListingCategory();

    /**
     * @param ListingListingCategory $listingListingCategory
     */
    public function setListingListingCategory(ListingListingCategory $listingListingCategory);
}