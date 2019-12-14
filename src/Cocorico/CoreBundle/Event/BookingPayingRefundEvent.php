<?php

namespace Cocorico\CoreBundle\Event;

use Cocorico\CoreBundle\Entity\Booking;

class BookingPayingRefundEvent extends BookingEvent
{
    protected $cancelable;

    public function __construct(Booking $booking)
    {
        parent::__construct($booking);
        $this->cancelable = false;
    }

    /**
     * @param bool $cancelable
     */
    public function setCancelable($cancelable)
    {
        $this->cancelable = $cancelable;
    }

    /**
     * @return bool
     */
    public function getCancelable()
    {
        return $this->cancelable;
    }

}
