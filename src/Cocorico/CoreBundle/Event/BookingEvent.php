<?php

namespace Cocorico\CoreBundle\Event;

use Cocorico\CoreBundle\Entity\Booking;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;

class BookingEvent extends Event
{
    protected $booking;
    protected $response;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param Booking $booking
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

}
