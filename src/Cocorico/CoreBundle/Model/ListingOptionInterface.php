<?php

namespace Cocorico\CoreBundle\Model;

use Cocorico\CoreBundle\Entity\Listing;

interface ListingOptionInterface
{
    /**
     * @param Listing $listing
     * @return mixed
     */
    public function setListing(Listing $listing);

    /**
     * @return Listing
     */
    public function getListing();

    public function mergeNewTranslations();

    public function getTranslations();

    public function getName();

}