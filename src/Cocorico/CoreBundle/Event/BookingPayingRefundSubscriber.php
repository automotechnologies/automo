<?php

namespace Cocorico\CoreBundle\Event;

use Cocorico\CoreBundle\Entity\Booking;
use Cocorico\CoreBundle\Entity\BookingPayingRefund;
use Cocorico\CoreBundle\Model\Manager\BookingPayingRefundManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingPayingRefundSubscriber implements EventSubscriberInterface
{
    protected $bookingPayingRefundManager;
    protected $entityManager;

    public function __construct(BookingPayingRefundManager $bookingPayingRefundManager)
    {
        $this->entityManager = $bookingPayingRefundManager->getEntityManager();
        $this->bookingPayingRefundManager = $bookingPayingRefundManager;
    }

    /**
     * Refund booking amount to asker when it's canceled
     *
     * @param BookingPayingRefundEvent  $event
     * @param  string                  $eventName
     * @param EventDispatcherInterface $dispatcher
     * @throws \Exception
     */
    public function onBookingRefund(BookingPayingRefundEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $booking = $event->getBooking();
        if ($booking->getStatus() == Booking::STATUS_PAYED) {
            //Get fees and refund amount
            $feeAndAmountToRefund = $this->bookingPayingRefundManager->getFeeAndAmountToRefundToAsker($booking);

            //If there is something to refund to asker
            if ($feeAndAmountToRefund["refund_amount"]) { //$feeAndAmountToRefund["fee_to_collect_while_refund"] ||
                $payingRefund = new BookingPayingRefund();
                $payingRefund->setBooking($booking);
                $payingRefund->setAmount($feeAndAmountToRefund["refund_amount"]);
                $payingRefund->setUser($booking->getUser());
                $payingRefund->setPayedAt(new \DateTime());
                $this->bookingPayingRefundManager->save($payingRefund);
                $this->entityManager->refresh($booking);

                $event->setCancelable(true);
            } elseif ($feeAndAmountToRefund["refund_percent"] == 0) {//nothing to refund to asker.
                $event->setCancelable(true);
            }
        }

        $event->setBooking($booking);
        $event->stopPropagation();
    }


    public static function getSubscribedEvents()
    {
        return array(
            BookingEvents::BOOKING_REFUND => array('onBookingRefund', 1),
        );
    }

}