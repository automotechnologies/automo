<?php

namespace Cocorico\CoreBundle\Event;


class ListingEvents
{
    /**
     * The LISTING_SHOW_QUERY event occurs when a listing is displayed.
     *
     * This event allows you to modify the SQL query.
     * The event listener method receives a Cocorico\CoreBundle\Event\ListingEvent instance.
     */
    const LISTING_SHOW_QUERY = 'cocorico.listing_show.query';

}