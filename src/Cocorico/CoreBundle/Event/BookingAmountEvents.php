<?php

namespace Cocorico\CoreBundle\Event;


class BookingAmountEvents
{
    /**
     * The BOOKING_PRE_AMOUNTS_SETTING event occurs before the booking amount and fees are computed and setted.
     *
     * This event allows you to modify default booking amount.
     * The event listener method receives a Cocorico\CoreBundle\Event\BookingAmountEvent instance.
     */
    const BOOKING_PRE_AMOUNTS_SETTING = 'cocorico.booking.pre_amounts_settings';

    /**
     * The BOOKING_POST_AMOUNTS_SETTING event occurs after the booking amount and fees are computed and setted.
     *
     * This event allows you to modify default total booking amount.
     * The event listener method receives a Cocorico\CoreBundle\Event\BookingAmountEvent instance.
     */
    const BOOKING_POST_AMOUNTS_SETTING = 'cocorico.booking.post_amounts_settings';


}