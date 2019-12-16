<?php

namespace Cocorico\CoreBundle\Event;

use Cocorico\CoreBundle\Model\ListingSearchRequest;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;

class ListingSearchEvent extends Event
{
    protected $listingSearchRequest;
    protected $queryBuilder;

    /**
     * @param ListingSearchRequest $listingSearchRequest
     * @param QueryBuilder         $queryBuilder
     */
    public function __construct(ListingSearchRequest $listingSearchRequest, QueryBuilder $queryBuilder)
    {
        $this->listingSearchRequest = $listingSearchRequest;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return ListingSearchRequest
     */
    public function getListingSearchRequest()
    {
        return $this->listingSearchRequest;
    }

    /**
     * @param ListingSearchRequest $listingSearchRequest
     */
    public function setListingSearchRequest($listingSearchRequest)
    {
        $this->listingSearchRequest = $listingSearchRequest;
    }


    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }


}
