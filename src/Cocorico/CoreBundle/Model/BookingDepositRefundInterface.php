<?php

namespace Cocorico\CoreBundle\Model;


use Cocorico\CoreBundle\Entity\Booking;

interface BookingDepositRefundInterface
{
    /**
     * @param Booking $booking
     * @return mixed
     */
    public function setBooking(Booking $booking);

    /**
     * @return Booking
     */
    public function getBooking();
}