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

use Cocorico\CoreBundle\Entity\BookingPayinRefund;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Booking Paying Refund Dashboard controller.
 *
 * @Route("/asker/booking-paying-refund")
 */
class BookingPayingRefundController extends Controller
{

    /**
     * Lists all booking payin refund.
     *
     * @Route("/{page}", name="cocorico_dashboard_booking_paying_refund_asker", defaults={"page" = 1})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $page
     *
     * @return Response
     */
    public function indexAction(Request $request, $page)
    {
        $bookingPayingRefundManager = $this->get('cocorico.booking_paying_refund.manager');
        $bookingPayingRefunds = $bookingPayingRefundManager->findByAsker(
            $this->getUser()->getId(),
            $page,
            array(BookingPayinRefund::STATUS_PAYED)
        );

        return $this->render(
            'CocoricoCoreBundle:Dashboard/BookingPayingRefund:index.html.twig',
            array(
                'booking_paying_refunds' => $bookingPayingRefunds,
                'pagination' => array(
                    'page' => $page,
                    'pages_count' => ceil($bookingPayingRefunds->count() / $bookingPayingRefundManager->maxPerPage),
                    'route' => $request->get('_route'),
                    'route_params' => $request->query->all()
                )
            )
        );
    }


    /**
     * Show booking Payin Refund bill.
     *
     * @Route("/{id}/show-bill", name="cocorico_dashboard_booking_paying_refund_show_bill_asker", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function showBillAction(Request $request, $id)
    {
        $bookingPayingRefundManager = $this->get('cocorico.booking_paying_refund.manager');
        try {
            $bookingPayingRefund = $bookingPayingRefundManager->findOneByAsker(
                $id,
                $this->getUser()->getId(),
                array(BookingPayinRefund::STATUS_PAYED)
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Bill not found');
        }

        return $this->render(
            'CocoricoCoreBundle:Dashboard/BookingPayingRefund:show_bill.html.twig',
            array(
                'booking_paying_refund' => $bookingPayingRefund,
            )
        );
    }


}
