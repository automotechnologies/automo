<?php

/*
 * This file is part of the Cocorico package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cocorico\CoreBundle\Controller\Dashboard\Asker;

use Cocorico\CoreBundle\Entity\Booking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Booking Paying Dashboard controller.
 *
 * @Route("/asker/booking-paying")
 */
class BookingPayingController extends Controller
{

    /**
     * Lists all booking paying.
     *
     * @Route("/{page}", name="cocorico_dashboard_booking_paying_asker", defaults={"page" = 1})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $page
     *
     * @return Response
     */
    public function indexAction(Request $request, $page)
    {
        $bookingManager = $this->get('cocorico.booking.manager');
        $bookings = $bookingManager->findPayedByAsker(
            $this->getUser()->getId(),
            $request->getLocale(),
            $page,
            array(Booking::STATUS_PAYED, Booking::STATUS_CANCELED_ASKER)
        );

        return $this->render(
            'CocoricoCoreBundle:Dashboard/BookingPayin:index.html.twig',
            array(
                'bookings' => $bookings,
                'pagination' => array(
                    'page' => $page,
                    'pages_count' => ceil($bookings->count() / $bookingManager->maxPerPage),
                    'route' => $request->get('_route'),
                    'route_params' => $request->query->all()
                )
            )
        );
    }


    /**
     * Show booking Bank Wire bill.
     *
     * @Route("/{id}/show-bill", name="cocorico_dashboard_booking_paying_show_bill_asker", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showBillAction(Request $request, $id)
    {
        $bookingManager = $this->get('cocorico.booking.manager');
        try {
            $booking = $bookingManager->findOneByAsker(
                $id,
                $this->getUser()->getId(),
                $request->getLocale(),
                array(Booking::STATUS_PAYED, Booking::STATUS_CANCELED_ASKER)
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Bill not found');
        }

        return $this->render(
            'CocoricoCoreBundle:Dashboard/BookingPayin:show_bill.html.twig',
            array(
                'booking' => $booking,
            )
        );
    }


}
