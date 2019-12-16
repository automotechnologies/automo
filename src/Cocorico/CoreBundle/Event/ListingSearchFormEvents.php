<?php

namespace Cocorico\CoreBundle\Event;


class ListingSearchFormEvents
{
    /**
     * The LISTING_SEARCH_RESULT_FORM_BUILD event is thrown each time listing search result form is build
     *
     * This event allows you to add form fields and validation on them.
     *
     * The event listener receives a \Cocorico\CoreBundle\Event\ListingFormBuilderEvent instance.
     */
    const LISTING_SEARCH_RESULT_FORM_BUILD = 'cocorico.listing_search.result.form.build';
}